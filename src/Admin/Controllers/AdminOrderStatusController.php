<?php
namespace BlackCart\Core\Admin\Controllers;

use App\Http\Controllers\RootAdminController;
use BlackCart\Core\Front\Models\ShopOrderStatus;
use Validator;

class AdminOrderStatusController extends RootAdminController
{

    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        $data = [
            'title' => trans('order_status.admin.list'),
            'title_action' => '<i class="fa fa-plus" aria-hidden="true"></i> ' . trans('order_status.admin.add_new_title'),
            'subTitle' => '',
            'icon' => 'fa fa-indent',
            'urlDeleteItem' => bc_route_admin('admin_order_status.delete'),
            'removeList' => 0, // 1 - Enable function delete list item
            'buttonRefresh' => 0, // 1 - Enable button refresh
            'buttonSort' => 0, // 1 - Enable button sort
            'css' => '', 
            'js' => '',
            'url_action' => bc_route_admin('admin_order_status.create'),
        ];

        $listTh = [
            'id' => trans('order_status.admin.id'),
            'name' => trans('order_status.admin.name'),
            'action' => trans('order_status.admin.action'),
        ];
        $obj = new ShopOrderStatus;
        $obj = $obj->orderBy('id', 'desc');
        $dataTmp = $obj->paginate(20);

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $dataTr[] = [
                'id' => $row['id'],
                'name' => $row['name'] ?? 'N/A',
                'action' => '
                    <a href="' . bc_route_admin('admin_order_status.edit', ['id' => $row['id']]) . '"><span title="' . trans('order_status.admin.edit') . '" type="button" class="btn btn-flat btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;

                  <span onclick="deleteItem(' . $row['id'] . ');"  title="' . trans('order_status.admin.delete') . '" class="btn btn-flat btn-danger"><i class="fas fa-trash-alt"></i></span>
                  ',
            ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->templatePathAdmin.'Component.pagination');
        $data['resultItems'] = trans('order_status.admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'item_total' => $dataTmp->total()]);

        $data['layout'] = 'index';
        return view($this->templatePathAdmin.'Order.order_status')
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
        ], [
            'name.required' => trans('validation.required'),
        ]);

        if ($validator->fails()) {
            // dd($validator->messages());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $dataInsert = [
            'name' => $data['name'],
        ];
        $obj = ShopOrderStatus::create($dataInsert);
//
        return redirect()->route('admin_order_status.edit', ['id' => $obj['id']])->with('success', trans('order_status.admin.create_success'));

    }

/**
 * Form edit
 */
public function edit($id)
{
    $order_status = ShopOrderStatus::find($id);
    if(!$order_status) {
        return 'No data';
    }
    $data = [
        'title' => trans('order_status.admin.list'),
        'title_action' => '<i class="fa fa-edit" aria-hidden="true"></i> ' . trans('order_status.admin.edit'),
        'subTitle' => '',
        'icon' => 'fa fa-indent',
        'urlDeleteItem' => bc_route_admin('admin_order_status.delete'),
        'removeList' => 0, // 1 - Enable function delete list item
        'buttonRefresh' => 0, // 1 - Enable button refresh
        'buttonSort' => 0, // 1 - Enable button sort
        'css' => '', 
        'js' => '',
        'url_action' => bc_route_admin('admin_order_status.edit', ['id' => $order_status['id']]),
        'order_status' => $order_status,
        'id' => $id,
    ];

    $listTh = [
        'id' => trans('order_status.admin.id'),
        'name' => trans('order_status.admin.name'),
        'action' => trans('order_status.admin.action'),
    ];
    $obj = new ShopOrderStatus;
    $obj = $obj->orderBy('id', 'desc');
    $dataTmp = $obj->paginate(20);

    $dataTr = [];
    foreach ($dataTmp as $key => $row) {
        $dataTr[] = [
            'id' => $row['id'],
            'name' => $row['name'] ?? 'N/A',
            'action' => '
                <a href="' . bc_route_admin('admin_order_status.edit', ['id' => $row['id']]) . '"><span title="' . trans('order_status.admin.edit') . '" type="button" class="btn btn-flat btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;

              <span onclick="deleteItem(' . $row['id'] . ');"  title="' . trans('order_status.admin.delete') . '" class="btn btn-flat btn-danger"><i class="fas fa-trash-alt"></i></span>
              ',
        ];
    }

    $data['listTh'] = $listTh;
    $data['dataTr'] = $dataTr;
    $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->templatePathAdmin.'Component.pagination');
    $data['resultItems'] = trans('order_status.admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'item_total' => $dataTmp->total()]);

    $data['layout'] = 'edit';
    return view($this->templatePathAdmin.'Odrer.order_status')
        ->with($data);
}


/**
 * update status
 */
    public function postEdit($id)
    {
        $data = request()->all();
        $dataOrigin = request()->all();
        $validator = Validator::make($dataOrigin, [
            'name' => 'required',
        ], [
            'name.required' => trans('validation.required'),
        ]);

        if ($validator->fails()) {
            // dd($validator->messages());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
//Edit
        $dataUpdate = [
            'name' => $data['name'],
        ];
        $obj = ShopOrderStatus::find($id);
        $obj->update($dataUpdate);
//
        return redirect()->back()->with('success', trans('order_status.admin.edit_success'));

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
            ShopOrderStatus::destroy($arrID);
            return response()->json(['error' => 0, 'msg' => '']);
        }
    }

}
