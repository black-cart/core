<?php
namespace BlackCart\Core\Admin\Controllers;

use App\Http\Controllers\RootAdminController;
use BlackCart\Core\Front\Models\ShopLanguage;
use Validator;
use BlackCart\Core\Admin\Models\AdminCategory;
class AdminCategoryController extends RootAdminController
{
    public $languages;

    public function __construct()
    {
        parent::__construct();
        $this->languages = ShopLanguage::getListActive();

    }

    public function index()
    {
        $categoriesTitle =  AdminCategory::getListTitleAdmin();
        
        //Process add content
        $sort_order = bc_clean(request('sort_order') ?? 'id_desc');
        $keyword    = bc_clean(request('keyword') ?? '');
        $arrSort = [
            'id__desc' => trans('category.admin.sort_order.id_desc'),
            'id__asc' => trans('category.admin.sort_order.id_asc'),
            'title__desc' => trans('category.admin.sort_order.title_desc'),
            'title__asc' => trans('category.admin.sort_order.title_asc'),
        ];
        
        $dataSearch = [
            'keyword'    => $keyword,
            'sort_order' => $sort_order,
            'arrSort'    => $arrSort,
        ];
        $dataTmp = (new AdminCategory)->getCategoryListAdmin($dataSearch);

        $data = [
            'title'         => trans('category.admin.list'),
            'urlDeleteItem' => bc_route_admin('admin_category.delete'),
            'removeList'    => 1, // 1 - Enable function delete list item
            'buttonRefresh' => 1, // 1 - Enable button refresh
            'buttonSort'    => 1, // 1 - Enable button sort
            'keyword'       => $keyword,
            'arrSort'       => $arrSort,
            'sort_order'    => $sort_order,
            'urlSort'       => bc_route_admin('admin_category.index', request()->except(['_token', '_pjax', 'sort_order'])),
            'pagination'    => $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->templatePathAdmin.'Component.pagination'),
            'resultItems'   => trans('category.admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'item_total' => $dataTmp->total()]),
            'dataCate'      => $dataTmp
        ];
        return view($this->templatePathAdmin.'Category.list')
            ->with($data);
    }

    /*
     * Form create new item in admin
     * @return [type] [description]
     */
    public function create()
    {
        $data = [
            'title' => trans('category.admin.add_new_title'),
            'subTitle' => '',
            'title_description' => trans('category.admin.add_new_des'),
            'icon' => 'fa fa-plus',
            'languages' => $this->languages,
            'category' => [],
            'categories' => (new AdminCategory)->getTreeCategoriesAdmin(),
            'url_action' => bc_route_admin('admin_category.create'),
        ];

        return view($this->templatePathAdmin.'Category.add_edit')
            ->with($data);
    }

    /*
     * Post create new item in admin
     * @return [type] [description]
     */
    public function postCreate()
    {
        $data = request()->all();

        $langFirst = array_key_first(bc_language_all()->toArray()); //get first code language active
        $data['alias'] = !empty($data['alias'])?$data['alias']:$data['descriptions'][$langFirst]['title'];
        $data['alias'] = bc_word_format_url($data['alias']);
        $data['alias'] = bc_word_limit($data['alias'], 100);

        $validator = Validator::make($data, [
                'parent'                 => 'required',
                'sort'                   => 'numeric|min:0',
                'alias'                  => 'required|unique:"'.AdminCategory::class.'",alias|regex:/(^([0-9A-Za-z\-_]+)$)/|string|max:100',
                'descriptions.*.title'   => 'required|string|max:200',
                'descriptions.*.keyword' => 'nullable|string|max:200',
                'descriptions.*.description' => 'nullable|string|max:300',
            ], [
                'descriptions.*.title.required' => trans('validation.required', ['attribute' => trans('category.title')]),
                'alias.regex' => trans('category.alias_validate'),
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
        }
        $dataInsert = [
            'image'    => $data['image'],
            'alias'    => $data['alias'],
            'parent'   => (int) $data['parent'],
            'top'      => !empty($data['top']) ? 1 : 0,
            'status'   => !empty($data['status']) ? 1 : 0,
            'sort'     => (int) $data['sort'],
        ];
        $category = AdminCategory::createCategoryAdmin($dataInsert);
        $dataDes = [];
        $languages = $this->languages;
        foreach ($languages as $code => $value) {
            $dataDes[] = [
                'category_id' => $category->id,
                'lang'        => $code,
                'title'       => $data['descriptions'][$code]['title'],
                'keyword'     => $data['descriptions'][$code]['keyword'],
                'description' => $data['descriptions'][$code]['description'],
            ];
        }
        AdminCategory::insertDescriptionAdmin($dataDes);

        bc_clear_cache('cache_category');

        return redirect()->route('admin_category.index')->with('success', trans('category.admin.create_success'));

    }

    /*
     * Form edit
     */
    public function edit($id)
    {
        $category = AdminCategory::getCategoryAdmin($id);

        if (!$category) {
            return redirect()->route('admin.data_not_found')->with(['url' => url()->full()]);
        }

        $data = [
            'title'             => trans('category.admin.edit'),
            'subTitle'          => '',
            'title_description' => '',
            'icon'              => 'fa fa-edit',
            'languages'         => $this->languages,
            'category'          => $category,
            'categories'        => (new AdminCategory)->getTreeCategoriesAdmin(),
            'url_action'        => bc_route_admin('admin_category.edit', ['id' => $category['id']]),
        ];
        return view($this->templatePathAdmin.'Category.add_edit')
            ->with($data);
    }

    /*
     * update status
     */
    public function postEdit($id)
    {
        $category = AdminCategory::getCategoryAdmin($id);
        if (!$category) {
            return redirect()->route('admin.data_not_found')->with(['url' => url()->full()]);
        }

        $data = request()->all();

        $langFirst = array_key_first(bc_language_all()->toArray()); //get first code language active
        $data['alias'] = !empty($data['alias'])?$data['alias']:$data['descriptions'][$langFirst]['title'];
        $data['alias'] = bc_word_format_url($data['alias']);
        $data['alias'] = bc_word_limit($data['alias'], 100);

        $validator = Validator::make($data, [
            'parent'                 => 'required',
            'sort'                   => 'numeric|min:0',
            'alias'                  => 'required|regex:/(^([0-9A-Za-z\-_]+)$)/|string|max:100|unique:"'.AdminCategory::class.'",alias,' . $id . '',
            'descriptions.*.title'   => 'required|string|max:200',
            'descriptions.*.keyword' => 'nullable|string|max:200',
            'descriptions.*.description' => 'nullable|string|max:300',
            ], [
                'descriptions.*.title.required' => trans('validation.required', ['attribute' => trans('category.title')]),
                'alias.regex'                   => trans('category.alias_validate'),
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
        }
        //Edit
        $dataUpdate = [
            'image'    => $data['image'],
            'alias'    => $data['alias'],
            'parent'   => $data['parent'],
            'sort'     => $data['sort'],
            'top'      => empty($data['top']) ? 0 : 1,
            'status'   => empty($data['status']) ? 0 : 1,
        ];

        $category->update($dataUpdate);
        $category->descriptions()->delete();
        $dataDes = [];
        foreach ($data['descriptions'] as $code => $row) {
            $dataDes[] = [
                'category_id' => $id,
                'lang'        => $code,
                'title'       => $row['title'],
                'keyword'     => $row['keyword'],
                'description' => $row['description'],
            ];
        }
        AdminCategory::insertDescriptionAdmin($dataDes);

        bc_clear_cache('cache_category');

    //
        return redirect()->route('admin_category.index')->with('success', trans('category.admin.edit_success'));

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
            $arrID = array_filter($arrID);
            $arrDontPermission = [];
            foreach ($arrID as $key => $id) {
                if(!$this->checkPermisisonItem($id)) {
                    $arrDontPermission[] = $id;
                }
            }
            if (count($arrDontPermission)) {
                return response()->json(['error' => 1, 'msg' => trans('admin.remove_dont_permisison') . ': ' . json_encode($arrDontPermission)]);
            }
            AdminCategory::destroy($arrID);
            bc_clear_cache('cache_category');
            return response()->json(['error' => 0, 'msg' => '']);
        }
    }

    /**
     * Check permisison item
     */
    public function checkPermisisonItem($id) {
        return AdminCategory::getCategoryAdmin($id);
    }

}
