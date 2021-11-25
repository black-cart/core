<?php
namespace BlackCart\Core\Admin\Controllers;

use App\Http\Controllers\RootAdminController;
use BlackCart\Core\Front\Models\ShopLanguage;
use BlackCart\Core\Admin\Models\AdminNews;
use Validator;

class AdminNewsController extends RootAdminController
{
    public $languages;

    public function __construct()
    {
        parent::__construct();
        $this->languages = ShopLanguage::getListActive();
    }

    public function index()
    {
        $sort_order = bc_clean(request('sort_order') ?? 'id_desc');
        $keyword    = bc_clean(request('keyword') ?? '');
        $arrSort = [
            'id__desc' => trans('news.admin.sort_order.id_desc'),
            'id__asc' => trans('news.admin.sort_order.id_asc'),
            'title__desc' => trans('news.admin.sort_order.title_desc'),
            'title__asc' => trans('news.admin.sort_order.title_asc'),
        ];
        $dataSearch = [
            'keyword'    => $keyword,
            'sort_order' => $sort_order,
            'arrSort'    => $arrSort,
        ];
        $dataNews = AdminNews::getNewsListAdmin($dataSearch);

        $data = [
            'title'         => trans('news.admin.list'),
            'urlDeleteItem' => bc_route_admin('admin_news.delete'),
            'removeList'    => 1, // 1 - Enable function delete list item
            'buttonRefresh' => 0, // 1 - Enable button refresh
            'buttonSort'    => 1, // 1 - Enable button sort
            'sort_order'    => $sort_order,
            'arrSort'       => $arrSort,
            'dataNews'      => $dataNews,
            'urlSort'       => bc_route_admin('admin_news.index', request()->except(['_token', '_pjax', 'sort_order'])),
            'keyword'       => $keyword,
            'pagination'    => $dataNews->appends(request()->except(['_token', '_pjax']))->links($this->templatePathAdmin.'Component.pagination'),
            'resultItems'   => trans('news.admin.result_item', ['item_from' => $dataNews->firstItem(), 'item_to' => $dataNews->lastItem(), 'item_total' => $dataNews->total()])
        ];

        return view($this->templatePathAdmin.'News.list')
            ->with($data);
    }

    /**
     * Form create new item in admin
     * @return [type] [description]
     */
    public function create()
    {
        $news = [];
        $data = [
            'title'             => trans('news.admin.add_new_title'),
            'subTitle'          => '',
            'title_description' => trans('news.admin.add_new_des'),
            'icon'              => 'fa fa-plus',
            'languages'         => $this->languages,
            'news'              => $news,
            'url_action'        => bc_route_admin('admin_news.create'),
        ];

        return view($this->templatePathAdmin.'News.add_edit')
            ->with($data);
    }

/**
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
            'alias' => 'required|regex:/(^([0-9A-Za-z\-_]+)$)/|string|max:100',
            'descriptions.*.title' => 'required|string|max:200',
            'descriptions.*.keyword' => 'nullable|string|max:200',
            'descriptions.*.description' => 'nullable|string|max:300',
            ], [
                'alias.regex' => trans('news.alias_validate'),
                'descriptions.*.title.required' => trans('validation.required', ['attribute' => trans('news.title')]),
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
        }

        $dataInsert = [
            'image'    => $data['image'],
            'sort'     => $data['sort'],
            'alias'    => $data['alias'],
            'status'   => !empty($data['status']) ? 1 : 0,
            'store_id' => session('adminStoreId'),
        ];
        $news = AdminNews::createNewsAdmin($dataInsert);
        $id = $news->id;
        $dataDes = [];
        $languages = $this->languages;
        foreach ($languages as $code => $value) {
            $dataDes[] = [
                'news_id'     => $id,
                'lang'        => $code,
                'title'       => $data['descriptions'][$code]['title'],
                'keyword'     => $data['descriptions'][$code]['keyword'],
                'description' => $data['descriptions'][$code]['description'],
                'content'     => $data['descriptions'][$code]['content'],
            ];
        }
        AdminNews::insertDescriptionAdmin($dataDes);
        bc_clear_cache('cache_news');

        return redirect()->route('admin_news.index')->with('success', trans('news.admin.create_success'));

    }

    /**
     * Form edit
     */
    public function edit($id)
    {

        $news = AdminNews::getNewsAdmin($id);
        if (!$news) {
            return redirect()->route('admin.data_not_found')->with(['url' => url()->full()]);
        }
        $data = [
            'title'             => trans('news.admin.edit'),
            'subTitle'          => '',
            'title_description' => '',
            'icon'              => 'fa fa-edit',
            'languages'         => $this->languages,
            'news'              => $news,
            'url_action'        => bc_route_admin('admin_news.edit', ['id' => $news['id']]),
        ];
        return view($this->templatePathAdmin.'News.add_edit')
            ->with($data);
    }

    /**
     * update status
     */
    public function postEdit($id)
    {
        $news = AdminNews::getNewsAdmin($id);
        if (!$news) {
            return redirect()->route('admin.data_not_found')->with(['url' => url()->full()]);
        }
        $data = request()->all();

        $langFirst = array_key_first(bc_language_all()->toArray()); //get first code language active
        $data['alias'] = !empty($data['alias'])?$data['alias']:$data['descriptions'][$langFirst]['title'];
        $data['alias'] = bc_word_format_url($data['alias']);
        $data['alias'] = bc_word_limit($data['alias'], 100);

        $validator = Validator::make($data, [
            'descriptions.*.title' => 'required|string|max:200',
            'descriptions.*.keyword' => 'nullable|string|max:200',
            'descriptions.*.description' => 'nullable|string|max:300',
            'alias' => 'required|regex:/(^([0-9A-Za-z\-_]+)$)/|string|max:100',
            ], [
                'alias.regex' => trans('news.alias_validate'),
                'descriptions.*.title.required' => trans('validation.required', ['attribute' => trans('news.title')]),
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
        }
    //Edit
        $dataUpdate = [
            'image' => $data['image'],
            'alias' => $data['alias'],
            'sort' => $data['sort'],
            'status' => !empty($data['status']) ? 1 : 0,
            'store_id'    => session('adminStoreId'),
        ];

        $news->update($dataUpdate);
        $news->descriptions()->delete();
        $dataDes = [];
        foreach ($data['descriptions'] as $code => $row) {
            $dataDes[] = [
                'news_id' => $id,
                'lang' => $code,
                'title' => $row['title'],
                'keyword' => $row['keyword'],
                'description' => $row['description'],
                'content' => $row['content'],
            ];
        }
        AdminNews::insertDescriptionAdmin($dataDes);
        bc_clear_cache('cache_news');

        return redirect()->route('admin_news.index')->with('success', trans('news.admin.edit_success'));

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
            AdminNews::destroy($arrID);
            bc_clear_cache('cache_news');

            return response()->json(['error' => 0, 'msg' => '']);
        }
    }

    /**
     * Check permisison item
     */
    public function checkPermisisonItem($id) {
        return AdminNews::getNewsAdmin($id);
    }
}
