<?php
namespace BlackCart\Core\Admin\Controllers;

use App\Http\Controllers\RootAdminController;
use BlackCart\Core\Front\Models\ShopEmailTemplate;
use BlackCart\Core\Admin\Models\AdminEmailTemplate;
use Validator;

class AdminEmailTemplateController extends RootAdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        //Process add content
        $dataSearch = [];
        $dataET = AdminEmailTemplate::getEmailTemplateListAdmin($dataSearch);

        $data = [
            'title'         => trans('email_template.admin.list'),
            'subTitle'      => '',
            'icon'          => 'fa fa-indent',
            'urlDeleteItem' => bc_route_admin('admin_email_template.delete'),
            'removeList'    => 0, // 1 - Enable function delete list item
            'buttonRefresh' => 0, // 1 - Enable button refresh
            'buttonSort'    => 0, // 1 - Enable button sort
            'css'           => '', 
            'js'            => '',
            'urlSort'       => bc_route_admin('admin_email_template.index', request()->except(['_token', '_pjax', 'sort_order'])),
            'dataET'        => $dataET,
            'pagination'    => $dataET->appends(request()->except(['_token', '_pjax']))->links($this->templatePathAdmin.'Component.pagination'),
            'resultItems'   =>trans('email_template.admin.result_item', ['item_from' => $dataET->firstItem(), 'item_to' => $dataET->lastItem(), 'item_total' => $dataET->total()])
        ];
        return view($this->templatePathAdmin.'EmailTemplate.list')
            ->with($data);
    }

    /**
     * Form create new item in admin
     * @return [type] [description]
     */
    public function create()
    {
        $data = [
            'title' => trans('email_template.admin.add_new_title'),
            'subTitle' => '',
            'title_description' => trans('email_template.admin.add_new_des'),
            'icon' => 'fa fa-plus',
            'arrayGroup' => $this->arrayGroup(),
            'ET' => [],
            'url_action' => bc_route_admin('admin_email_template.create'),
        ];
        return view($this->templatePathAdmin.'EmailTemplate.add_edit')
            ->with($data);
    }

/**
 * Post create new item in admin
 * @return [type] [description]
 */
    public function postCreate()
    {
        $data = request()->all();
        $dataOrigin = request()->all();
        $validator = Validator::make($dataOrigin, [
            'name' => 'required',
            'group' => 'required',
            'text' => 'required',
        ]);

        if ($validator->fails()) {
            // dd($validator->messages());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $dataInsert = [
            'name'     => $data['name'],
            'group'    => $data['group'],
            'text'     => $data['text'],
            'status'   => !empty($data['status']) ? 1 : 0,
            'store_id' => session('adminStoreId'),
        ];
        ShopEmailTemplate::createEmailTemplateAdmin($dataInsert);

        return redirect()->route('admin_email_template.index')->with('success', trans('email_template.admin.create_success'));

    }

    /**
     * Form edit
     */
    public function edit($id)
    {
        $emailTemplate = AdminEmailTemplate::getEmailTemplateAdmin($id);
        if (!$emailTemplate) {
            return redirect()->route('admin.data_not_found')->with(['url' => url()->full()]);
        }
        $data = [
            'title' => trans('email_template.admin.edit'),
            'subTitle' => '',
            'title_description' => '',
            'icon' => 'fa fa-edit',
            'ET' => $emailTemplate,
            'arrayGroup' => $this->arrayGroup(),
            'url_action' => bc_route_admin('admin_email_template.edit', ['id' => $emailTemplate['id']]),
        ];
        return view($this->templatePathAdmin.'EmailTemplate.add_edit')
            ->with($data);
    }

/**
 * update status
 */
    public function postEdit($id)
    {
        $emailTemplate = AdminEmailTemplate::getEmailTemplateAdmin($id);
        if (!$emailTemplate) {
            return redirect()->route('admin.data_not_found')->with(['url' => url()->full()]);
        }
        $data = request()->all();
        $dataOrigin = request()->all();
        $validator = Validator::make($dataOrigin, [
            'name' => 'required',
            'group' => 'required',
            'text' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        //Edit
        $dataUpdate = [
            'name'     => $data['name'],
            'group'    => $data['group'],
            'text'     => $data['text'],
            'status'   => !empty($data['status']) ? 1 : 0,
            'store_id' => session('adminStoreId'),
        ];
        $emailTemplate->update($dataUpdate);

        return redirect()->route('admin_email_template.index')->with('success', trans('email_template.admin.edit_success'));

    }

    /*
    Delete list item
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
            ShopEmailTemplate::destroy($arrID);
            return response()->json(['error' => 0, 'msg' => '']);
        }
    }

    /**
     * Get list variables support for email template
     *
     * @return json
     */
    public function listVariable()
    {
        $key = request('key');
        $list = [];
        switch ($key) {
            case 'order_success_to_customer':
            case 'order_success_to_admin':
                $list = [
                    '$title',
                    '$orderID',
                    '$toname',
                    '$firstName',
                    '$lastName',
                    '$address',
                    '$address1',
                    '$address2',
                    '$address3',
                    '$email',
                    '$phone',
                    '$comment',
                    '$orderDetail',
                    '$subtotal',
                    '$shipping',
                    '$discount',
                    '$total',
                ];
                break;

            case 'forgot_password':
                $list = [
                    '$title',
                    '$reason_sednmail',
                    '$note_sendmail',
                    '$note_access_link',
                    '$reset_link',
                    '$reset_button',
                ];
                break;

            case 'customer_verify':
                $list = [
                    '$title',
                    '$reason_sednmail',
                    '$note_sendmail',
                    '$note_access_link',
                    '$url_verify',
                    '$button',
                ];
                break;

            case 'contact_to_admin':
                $list = [
                    '$title',
                    '$name',
                    '$email',
                    '$phone',
                    '$content',
                ];
                break;
            case 'welcome_customer':
                    $list = [
                        '$title',
                        '$first_name',
                        '$last_name',
                        '$email',
                        '$phone',
                        '$password',
                        '$address1',
                        '$address2',
                        '$address3',
                        '$country',
                    ];
                    break;
            default:
                # code...
                break;
        }
        return response()->json($list);
    }

    public function arrayGroup()
    {
        return  [
            'order_success_to_admin' => trans('email.email_action.order_success_to_admin'),
            'order_success_to_customer' => trans('email.email_action.order_success_to_cutomer'),
            'forgot_password' => trans('email.email_action.forgot_password'),
            'customer_verify' => trans('email.email_action.customer_verify'),
            'welcome_customer' => trans('email.email_action.welcome_customer'),
            'contact_to_admin' => trans('email.email_action.contact_to_admin'),
            'other' => trans('email.email_action.other'),
        ];
    }

    /**
     * Check permisison item
     */
    public function checkPermisisonItem($id) {
        return AdminEmailTemplate::getEmailTemplateAdmin($id);
    }
}
