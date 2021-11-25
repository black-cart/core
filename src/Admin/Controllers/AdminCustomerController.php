<?php
namespace BlackCart\Core\Admin\Controllers;

use App\Http\Controllers\RootAdminController;
use BlackCart\Core\Front\Models\ShopCountry;
use BlackCart\Core\Front\Models\ShopLanguage;
use BlackCart\Core\Admin\Models\AdminCustomer;
use BlackCart\Core\Front\Models\ShopCustomField;
use BlackCart\Core\Front\Models\ShopCustomFieldDetail;
use BlackCart\Core\Front\Controllers\Auth\AuthTrait;
use Validator;

class AdminCustomerController extends RootAdminController
{
    use AuthTrait;
    public $languages, $countries;

    public function __construct()
    {
        parent::__construct();
        $this->languages = ShopLanguage::getListActive();
        $this->countries = ShopCountry::getListAll();

    }

    public function index()
    {
        $sort_order = bc_clean(request('sort_order') ?? 'id_desc');
        $keyword    = bc_clean(request('keyword') ?? '');
        $arrSort = [
            'id__desc' => trans('customer.admin.sort_order.id_desc'),
            'id__asc' => trans('customer.admin.sort_order.id_asc'),
            'first_name__desc' => trans('customer.admin.sort_order.first_name_desc'),
            'first_name__asc' => trans('customer.admin.sort_order.first_name_asc'),
            'last_name__desc' => trans('customer.admin.sort_order.last_name_desc'),
            'last_name__asc' => trans('customer.admin.sort_order.last_name_asc'),
        ];

        $dataSearch = [
            'keyword'    => $keyword,
            'sort_order' => $sort_order,
            'arrSort'    => $arrSort,
        ];
        $dataCustomer = (new AdminCustomer)->getCustomerListAdmin($dataSearch);

        $data = [
            'title'         => trans('customer.admin.list'),
            'subTitle'      => '',
            'icon'          => 'fa fa-indent',
            'urlDeleteItem' => bc_route_admin('admin_customer.delete'),
            'removeList'    => 1, // 1 - Enable function delete list item
            'buttonRefresh' => 0, // 1 - Enable button refresh
            'buttonSort'    => 1, // 1 - Enable button sort
            'css'           => '', 
            'js'            => '',
            'dataCustomer'  => $dataCustomer,
            'sort_order'    => $sort_order,
            'keyword'       => $keyword,
            'arrSort'       => $arrSort,
            'country'       => $this->countries,
            'urlSort'       => bc_route_admin('admin_customer.index', request()->except(['_token', '_pjax', 'sort_order'])),
            'resultItems'   => trans('customer.admin.result_item', ['item_from' => $dataCustomer->firstItem(), 'item_to' => $dataCustomer->lastItem(), 'item_total' => $dataCustomer->total()]),
            'pagination'    => $dataCustomer->appends(request()->except(['_token', '_pjax']))->links($this->templatePathAdmin.'Component.pagination'),
        ];

        return view($this->templatePathAdmin.'Customer.list')
            ->with($data);
    }

/**
 * Form create new item in admin
 * @return [type] [description]
 */
    public function create()
    {
        $data = [
            'title'             => trans('customer.admin.add_new_title'),
            'subTitle'          => '',
            'title_description' => trans('customer.admin.add_new_des'),
            'icon'              => 'fa fa-plus',
            'countries'         => (new ShopCountry)->getCodeAll(),
            'customer'          => [],
            'url_action'        => bc_route_admin('admin_customer.create'),
            'customFields'         => (new ShopCustomField)->getCustomField($type = 'customer'),

        ];

        return view($this->templatePathAdmin.'Customer.add_edit')
            ->with($data);
    }

/**
 * Post create new item in admin
 * @return [type] [description]
 */
    public function postCreate()
    {
        $data = request()->all();
        $data['status'] = empty($data['status']) ? 0 : 1;
        $data['store_id'] = session('adminStoreId');
        $dataMapping = $this->mappingValidator($data);
        $validator =  Validator::make($data, $dataMapping['validate'], $dataMapping['messages']);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $customer = AdminCustomer::createCustomer($dataMapping['dataInsert']);

        //Insert custom fields
        if (!empty($data['fields'])) {
            $dataField = [];
            foreach ($data['fields'] as $key => $value) {
                $field = (new ShopCustomField)->where('code', $key)->where('type', 'customer')->first();
                if ($field) {
                    $dataField[] = [
                        'custom_field_id' => $field->id,
                        'rel_id' => $customer->id,
                        'text' => trim($value),
                    ];
                }
            }
            if ($dataField) {
                (new ShopCustomFieldDetail)->insert($dataField);
            }
        }

        return redirect()->route('admin_customer.index')->with('success', trans('customer.admin.create_success'));

    }

/**
 * Form edit
 */
    public function edit($id)
    {
        $customer = (new AdminCustomer)->getCustomerAdmin($id);
        if (!$customer) {
            return redirect()->route('admin.data_not_found')->with(['url' => url()->full()]);
        }
        $data = [
            'title' => trans('customer.admin.edit'),
            'subTitle' => '',
            'title_description' => '',
            'icon' => 'fa fa-edit',
            'customer' => $customer,
            'countries' => (new ShopCountry)->getCodeAll(),
            'addresses' => $customer->addresses,
            'url_action' => bc_route_admin('admin_customer.edit', ['id' => $customer['id']]),
            'customFields'  => (new ShopCustomField)->getCustomField($type = 'customer'),
        ];
        return view($this->templatePathAdmin.'Customer.add_edit')
            ->with($data);
    }

    /**
     * update status
     */
    public function postEdit($id)
    {
        $data = request()->all();
        $customer = (new AdminCustomer)->getCustomerAdmin($id);
        if (!$customer) {
            return redirect()->route('admin.data_not_found')->with(['url' => url()->full()]);
        }

        $data['status'] = empty($data['status']) ? 0 : 1;
        $data['store_id'] = session('adminStoreId');
        $data['id'] = $id;
        $dataMapping = $this->mappingValidatorEdit($data);

        $validator =  Validator::make($data, $dataMapping['validate'], $dataMapping['messages']);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        AdminCustomer::updateInfo($dataMapping['dataUpdate'], $id);

        //Update custom field
        if (!empty($data['fields'])) {
            (new ShopCustomFieldDetail)
                ->join(BC_DB_PREFIX.'shop_custom_field', BC_DB_PREFIX.'shop_custom_field.id', BC_DB_PREFIX.'shop_custom_field_detail.custom_field_id')
                ->select('code', 'name', 'text')
                ->where(BC_DB_PREFIX.'shop_custom_field_detail.rel_id', $customer->id)
                ->where(BC_DB_PREFIX.'shop_custom_field.type', 'customer')
                ->delete();

            $dataField = [];
            foreach ($data['fields'] as $key => $value) {
                $field = (new ShopCustomField)->where('code', $key)->where('type', 'customer')->first();
                if ($field) {
                    $dataField[] = [
                        'custom_field_id' => $field->id,
                        'rel_id' => $customer->id,
                        'text' => trim($value),
                    ];
                }
            }
            if ($dataField) {
                (new ShopCustomFieldDetail)->insert($dataField);
            }
        }

        return redirect()->route('admin_customer.index')->with('success', trans('customer.admin.edit_success'));

    }

    /*
    Delete list Item
    Need mothod destroy to boot deleting in model
    */
    public function deleteList()
    {
        if (!request()->ajax()) {
            return response()->json(['error' => 1, 'msg' => trans('admin.method_not_allow')]);
        } else {
            $ids = request('ids');
            $arrID = explode(',', $ids);
            $arrDontPermission = [];
            foreach ($arrID as $key => $id) {
                if(!$this->checkPermisisonItem($id)) {
                    $arrDontPermission[] = $id;
                }
            }
            if (count($arrDontPermission)) {
                return response()->json(['error' => 1, 'msg' => trans('admin.remove_dont_permisison') . ': ' . json_encode($arrDontPermission)]);
            }
            AdminCustomer::destroy($arrID);
            return response()->json(['error' => 0, 'msg' => '']);
        }
    }


    /**
     * Render address detail
     * @return [view]
     */
    public function updateAddress($id)
    {
        $address =  AdminCustomer::getAddress($id);
        if ($address) {
            $title = trans('account.address_detail').' #'.$address->id;
        } else {
            $title = trans('account.address_detail_notfound');
        }
        return view($this->templatePathAdmin.'Customer.edit_address')
        ->with(
            [
            'title'       => $title,
            'address'     => $address,
            'customer'    => (new AdminCustomer)->getCustomerAdmin($address->customer_id),
            'countries'   => ShopCountry::getCodeAll(),
            'layout_page' => 'shop_profile',
            'url_action'  => bc_route_admin('admin_customer.update_address', ['id' => $id]),
            ]
        );

    }

    /**
     * Process update address
     *
     *
     * @return  [redirect] 
     */
    public function postUpdateAddress($id)
    {
        $data = request()->all();
        $address =  AdminCustomer::getAddress($id);
        $dataUpdate = [
            'first_name' => $data['first_name'],
            'address1' => $data['address1'],
        ];
        $validate = [
            'first_name' => 'required|string|max:100',
        ];
        
        if (bc_config_admin('customer_lastname')) {
            if (bc_config_admin('customer_lastname_required')) {
                $validate['last_name'] = 'required|string|max:100';
            } else {
                $validate['last_name'] = 'nullable|string|max:100';
            }
            $dataUpdate['last_name'] = $data['last_name']??'';
        }

        if (bc_config_admin('customer_address1')) {
            if (bc_config_admin('customer_address1_required')) {
                $validate['address1'] = 'required|string|max:100';
            } else {
                $validate['address1'] = 'nullable|string|max:100';
            }
            $dataUpdate['address1'] = $data['address1']??'';
        }

        if (bc_config_admin('customer_address2')) {
            if (bc_config_admin('customer_address2_required')) {
                $validate['address2'] = 'required|string|max:100';
            } else {
                $validate['address2'] = 'nullable|string|max:100';
            }
            $dataUpdate['address2'] = $data['address2']??'';
        }

        if (bc_config_admin('customer_address3')) {
            if (bc_config_admin('customer_address3_required')) {
                $validate['address3'] = 'required|string|max:100';
            } else {
                $validate['address3'] = 'nullable|string|max:100';
            }
            $dataUpdate['address3'] = $data['address3']??'';
        }

        if (bc_config_admin('customer_phone')) {
            if (bc_config_admin('customer_phone_required')) {
                $validate['phone'] = 'required|regex:/^0[^0][0-9\-]{7,13}$/';
            } else {
                $validate['phone'] = 'nullable|regex:/^0[^0][0-9\-]{7,13}$/';
            }
            $dataUpdate['phone'] = $data['phone']??'';
        }

        if (bc_config_admin('customer_country')) {
            $arraycountry = (new ShopCountry)->pluck('code')->toArray();
            if (bc_config_admin('customer_country_required')) {
                $validate['country'] = 'required|string|min:2|in:'. implode(',', $arraycountry);
            } else {
                $validate['country'] = 'nullable|string|min:2|in:'. implode(',', $arraycountry);
            }
            
            $dataUpdate['country'] = $data['country']??'';
        }

        if (bc_config_admin('customer_postcode')) {
            if (bc_config_admin('customer_postcode_required')) {
                $validate['postcode'] = 'required|min:5';
            } else {
                $validate['postcode'] = 'nullable|min:5';
            }
            $dataUpdate['postcode'] = $data['postcode']??'';
        }

        if (bc_config_admin('customer_name_kana')) {
            if (bc_config_admin('customer_name_kana_required')) {
                $validate['first_name_kana'] = 'required|string|max:100';
                $validate['last_name_kana'] = 'required|string|max:100';
            } else {
                $validate['first_name_kana'] = 'nullable|string|max:100';
                $validate['last_name_kana'] = 'nullable|string|max:100';
            }
            $dataUpdate['first_name_kana'] = $data['first_name_kana']?? '';
            $dataUpdate['last_name_kana'] = $data['last_name_kana']?? '';
        }

        $messages = [
            'last_name.required'  => trans('validation.required',['attribute'=> trans('account.last_name')]),
            'first_name.required' => trans('validation.required',['attribute'=> trans('account.first_name')]),
            'address1.required'   => trans('validation.required',['attribute'=> trans('account.address1')]),
            'address2.required'   => trans('validation.required',['attribute'=> trans('account.address2')]),
            'address3.required'   => trans('validation.required',['attribute'=> trans('account.address3')]),
            'phone.required'      => trans('validation.required',['attribute'=> trans('account.phone')]),
            'country.required'    => trans('validation.required',['attribute'=> trans('account.country')]),
            'postcode.required'   => trans('validation.required',['attribute'=> trans('account.postcode')]),
            'phone.regex'         => trans('customer.phone_regex'),
            'postcode.min'        => trans('validation.min',['attribute'=> trans('account.postcode')]),
            'country.min'         => trans('validation.min',['attribute'=> trans('account.country')]),
            'first_name.max'      => trans('validation.max',['attribute'=> trans('account.first_name')]),
            'address1.max'        => trans('validation.max',['attribute'=> trans('account.address1')]),
            'address2.max'        => trans('validation.max',['attribute'=> trans('account.address2')]),
            'address3.max'        => trans('validation.max',['attribute'=> trans('account.address3')]),
            'last_name.max'       => trans('validation.max',['attribute'=> trans('account.last_name')]),
        ];

        $v = Validator::make(
            $dataUpdate, 
            $validate, 
            $messages
        );
        if ($v->fails()) {
            return redirect()->back()->withErrors($v->errors());
        }

        $address->update(bc_clean($dataUpdate));

        if (!empty($data['default'])) {
            $customer = (new AdminCustomer)->getCustomerAdmin($address->customer_id);
            $customer->address_id = $id;
            $customer->save();
        }
        return redirect()->route('admin_customer.edit', ['id' => $address->customer_id])
            ->with(['success' => trans('account.update_success')]);
    }

    /**
     * Get address detail 
     *
     * @return  [json] 
     */
    public function deleteAddress() {
        $id = request('id');
        AdminCustomer::deleteAddress($id);
        return json_encode(['error' => 0, 'msg' => trans('account.delete_address_success')]);
    }

    /**
     * Check permisison item
     */
    public function checkPermisisonItem($id) {
        return (new AdminCustomer)->getCustomerAdmin($id);
    }

}
