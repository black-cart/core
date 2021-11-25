<?php
namespace BlackCart\Core\Admin\Controllers;

use App\Http\Controllers\RootAdminController;
use BlackCart\Core\Front\Models\ShopAttributeGroup;
use BlackCart\Core\Front\Models\ShopProductProperty;
use BlackCart\Core\Front\Models\ShopLanguage;
use BlackCart\Core\Admin\Models\AdminProduct;

class AdminReportController extends RootAdminController
{
    public $languages, $kinds, $properties, $attributeGroup;

    public function __construct()
    {
        parent::__construct();
        $this->languages = ShopLanguage::getListActive();
        $this->attributeGroup = ShopAttributeGroup::getListAll();
        $this->kinds = [
            BC_PRODUCT_SINGLE => trans('product.kinds.single'),
            BC_PRODUCT_BUILD => trans('product.kinds.build'),
            BC_PRODUCT_GROUP => trans('product.kinds.group'),
        ];
        $this->properties = (new ShopProductProperty)->pluck('name', 'code')->toArray();

    }

    public function product()
    {
        $sort_order = bc_clean(request('sort_order') ?? 'id_desc');
        $keyword    = bc_clean(request('keyword') ?? '');
        $arrSort = [
            'id__desc' => trans('product.admin.sort_order.id_desc'),
            'id__asc' => trans('product.admin.sort_order.id_asc'),
            'name__desc' => trans('product.admin.sort_order.name_desc'),
            'name__asc' => trans('product.admin.sort_order.name_asc'),
            'sold__desc' => trans('product.admin.sort_order.sold_desc'),
            'sold__asc' => trans('product.admin.sort_order.sold_asc'),
            'view__desc' => trans('product.admin.sort_order.view_desc'),
            'view__asc' => trans('product.admin.sort_order.view_asc'),
        ];
        $dataSearch = [
            'keyword'    => $keyword,
            'sort_order' => $sort_order,
            'arrSort'    => $arrSort,
        ];

        $dataReport = (new AdminProduct)->getProductListAdmin($dataSearch);

        $data = [
            'title' => trans('product.admin.list'),
            'subTitle' => '',
            'icon' => 'fa fa-indent',
            'urlDeleteItem' => '',
            'removeList' => 0, // 1 - Enable function delete list item
            'buttonRefresh' => 0, // 1 - Enable button refresh
            'buttonSort' => 1, // 1 - Enable button sort
            'css' => '', 
            'js' => '',
            'urlSort'   => bc_route_admin('admin_report.product', request()->except(['_token', '_pjax', 'sort_order'])),
            'sort_order' => $sort_order,
            'keyword' => $keyword,
            'arrSort' => $arrSort,
            'kinds' => $this->kinds,
            'dataReport' => $dataReport,
            'pagination' => $dataReport->appends(request()->except(['_token', '_pjax']))->links($this->templatePathAdmin.'Component.pagination'),
            'resultItems' => trans('product.admin.result_item', ['item_from' => $dataReport->firstItem(), 'item_to' => $dataReport->lastItem(), 'item_total' => $dataReport->total()]),
        ];

        return view($this->templatePathAdmin.'Report.product')
            ->with($data);
    }

}
