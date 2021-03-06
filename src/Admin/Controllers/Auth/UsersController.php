<?php
namespace BlackCart\Core\Admin\Controllers\Auth;

use BlackCart\Core\Admin\Admin;
use BlackCart\Core\Admin\Models\AdminPermission;
use BlackCart\Core\Admin\Models\AdminRole;
use BlackCart\Core\Admin\Models\AdminUser;
use BlackCart\Core\Admin\Models\AdminUserStore;
use App\Http\Controllers\RootAdminController;
use BlackCart\Core\Admin\Models\AdminStore;
use Validator;

class UsersController extends RootAdminController
{
    public $stores, $permissions, $roles;
    public function __construct()
    {
        parent::__construct();
        $this->stores      = AdminStore::getListAll();
        $this->permissions = AdminPermission::pluck('name', 'id')->all();
        $this->roles       = AdminRole::pluck('name', 'id')->all();
    }

    public function index()
    {
        $data = [
            'title'         => trans('user.admin.list'),
            'subTitle'      => '',
            'icon'          => 'fa fa-indent',
            'urlDeleteItem' => bc_route_admin('admin_user.delete'),
            'removeList'    => 0, // 1 - Enable function delete list item
            'buttonRefresh' => 1, // 1 - Enable button refresh
            'buttonSort'    => 1, // 1 - Enable button sort
            'css'           => '', 
            'js'            => '',
        ];
        //Process add content
        $data['menuRight']    = bc_config_group('menuRight', \Request::route()->getName());
        $data['menuLeft']     = bc_config_group('menuLeft', \Request::route()->getName());
        $data['topMenuRight'] = bc_config_group('topMenuRight', \Request::route()->getName());
        $data['topMenuLeft']  = bc_config_group('topMenuLeft', \Request::route()->getName());
        $data['blockBottom']  = bc_config_group('blockBottom', \Request::route()->getName());
        $data['stores']       = $this->stores;

        $listTh = [
            'id'         => trans('user.id'),
            'username'   => trans('user.user_name'),
            'name'       => trans('user.name'),
            'roles'      => trans('user.roles'),
            'permission' => trans('user.permission'),
            'created_at' => trans('user.created_at'),
            'action'     => trans('user.admin.action'),
        ];
        $sort_order = request('sort_order') ?? 'id_desc';
        $keyword = request('keyword') ?? '';
        $arrSort = [
            'id__desc'       => trans('user.admin.sort_order.id_desc'),
            'id__asc'        => trans('user.admin.sort_order.id_asc'),
            'username__desc' => trans('user.admin.sort_order.username_desc'),
            'username__asc'  => trans('user.admin.sort_order.username_asc'),
            'name__desc'     => trans('user.admin.sort_order.name_desc'),
            'name__asc'      => trans('user.admin.sort_order.name_asc'),
        ];
        $obj = new AdminUser;

        if ($keyword) {
            $obj = $obj->whereRaw('(id = ' . (int) $keyword . '  OR name like "%' . $keyword . '%" OR username like "%' . $keyword . '%"  )');
        }
        if ($sort_order && array_key_exists($sort_order, $arrSort)) {
            $field = explode('__', $sort_order)[0];
            $sort_field = explode('__', $sort_order)[1];
            $obj = $obj->orderBy($field, $sort_field);

        } else {
            $obj = $obj->orderBy('id', 'desc');
        }
        $dataTmp = $obj->paginate(20);

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $showRoles = '';
            if ($row['roles']->count()) {
                foreach ($row['roles'] as $key => $rols) {
                    $showRoles .= '<span class="badge badge-success">' . $rols->name . '</span> ';
                }
            }
            $showPermission = '';
            if ($row['permissions']->count()) {
                foreach ($row['permissions'] as $key => $p) {
                    $showPermission .= '<span class="badge badge-success">' . $p->name . '</span> ';
                }
            }
            $dataTr[] = [
                'id' => $row['id'],
                'username' => $row['username'],
                'name' => $row['name'],
                'roles' => $showRoles,
                'permission' => $showPermission,
                'created_at' => $row['created_at'],
                'action' => '
                    <a href="' . bc_route_admin('admin_user.edit', ['id' => $row['id']]) . '"><span title="' . trans('user.admin.edit') . '" type="button" class="btn btn-flat btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;
                    ' . ((Admin::user()->id == $row['id'] || in_array($row['id'], BC_GUARD_ADMIN)) ? '' : '<span onclick="deleteItem(' . $row['id'] . ');"  title="' . trans('admin.delete') . '" class="btn btn-flat btn-danger"><i class="fas fa-trash-alt"></i></span>')
                ,
            ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->templatePathAdmin.'Component.pagination');
        $data['resultItems'] = trans('user.admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'item_total' => $dataTmp->total()]);

//menuRight
        $data['menuRight'][] = '<a href="' . bc_route_admin('admin_user.create') . '" class="btn  btn-success  btn-flat" title="New" id="button_create_new">
                           <i class="fa fa-plus" title="'.trans('admin.add_new').'"></i>
                           </a>';
//=menuRight

//menuSort
        $optionSort = '';
        foreach ($arrSort as $key => $status) {
            $optionSort .= '<option  ' . (($sort_order == $key) ? "selected" : "") . ' value="' . $key . '">' . $status . '</option>';
        }

        $data['urlSort'] = bc_route_admin('admin_user.index', request()->except(['_token', '_pjax', 'sort_order']));

        $data['optionSort'] = $optionSort;
//=menuSort

//menuSearch
        $data['topMenuRight'][] = '
                <form action="' . bc_route_admin('admin_user.index') . '" id="button_search">
                <div class="input-group input-group" style="width: 250px;">
                    <input type="text" name="keyword" class="form-control rounded-0 float-right" placeholder="' . trans('user.admin.search_place') . '" value="' . $keyword . '">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                </form>';
//=menuSearch


        return view($this->templatePathAdmin.'screen.list')
            ->with($data);
    }

/**
 * Form create new item in admin
 * @return [type] [description]
 */
    public function create()
    {
        $data = [
            'title'             => trans('user.admin.add_new_title'),
            'subTitle'          => '',
            'title_description' => trans('user.admin.add_new_des'),
            'icon'              => 'fa fa-plus',
            'user'              => [],
            'roles'             => $this->roles,
            'permissions'       => $this->permissions,
            'url_action'        => bc_route_admin('admin_user.create'),
            'stores'            => $this->stores,

        ];

        return view($this->templatePathAdmin.'Auth.user')
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
            'name'     => 'required|string|max:100',
            'store'    => 'required',
            'username' => 'required|regex:/(^([0-9A-Za-z@\._]+)$)/|unique:"'.AdminUser::class.'",username|string|max:100|min:3',
            'avatar'   => 'nullable|string|max:255',
            'password' => 'required|string|max:60|min:6|confirmed',
            'email'    => 'required|string|email|max:255|unique:"'.AdminUser::class.'",email',
        ], [
            'username.regex' => trans('user.username_validate'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $store = $data['store'] ?? 1;

        $dataInsert = [
            'name'     => $data['name'],
            'username' => strtolower($data['username']),
            'avatar'   => $data['avatar'],
            'email'    => strtolower($data['email']),
            'password' => bcrypt($data['password']),
        ];

        $user = AdminUser::createUser($dataInsert);

        $roles = $data['roles'] ?? [];
        $permission = $data['permission'] ?? [];

        //Process role special
        if(in_array(1, $roles)) {
            // If group admin
            $roles = [1];
            $permission = [];
        } else if(in_array(2, $roles)) {
            // If group onlyview
            $roles = [2];
            $permission = [];
        }
        //End process role special

        //Insert roles
        if ($roles) {
            $user->roles()->attach($roles);
        }
        //Insert permission
        if ($permission) {
            $user->permissions()->attach($permission);
        }

        //Insert store
        $user->stores()->attach([$store]);

        return redirect()->route('admin_user.index')->with('success', trans('user.admin.create_success'));

    }

/**
 * Form edit
 */
    public function edit($id)
    {
        $user = AdminUser::find($id);
        if ($user === null) {
            return 'no data';
        }
        $data = [
            'title'             => trans('user.admin.edit'),
            'subTitle'          => '',
            'title_description' => '',
            'icon'              => 'fa fa-edit',
            'user'              => $user,
            'roles'             => $this->roles,
            'permissions'       => $this->permissions,
            'stores'            => $this->stores,
            'url_action'        => bc_route_admin('admin_user.edit', ['id' => $user['id']]),
            'storesPivot'       => AdminUserStore::where('user_id', $id)->pluck('store_id')->all(),
            'isAllStore'        => ($user->isAdministrator() || $user->isViewAll()) ? 1: 0,

        ];
        return view($this->templatePathAdmin.'Auth.user')
            ->with($data);
    }

/**
 * update status
 */
    public function postEdit($id)
    {
        $user = AdminUser::find($id);
        $data = request()->all();
        $dataOrigin = request()->all();
        $validator = Validator::make($dataOrigin, [
            'name'     => 'required|string|max:100',
            'store'    => 'required',
            'username' => 'required|regex:/(^([0-9A-Za-z@\._]+)$)/|unique:"'.AdminUser::class.'",username,' . $user->id . '|string|max:100|min:3',
            'avatar'   => 'nullable|string|max:255',
            'password' => 'nullable|string|max:60|min:6|confirmed',
            'email'    => 'required|string|email|max:255|unique:"'.AdminUser::class.'",email,' . $user->id,
        ], [
            'username.regex' => trans('user.username_validate'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
//Edit
        $store = $data['store'] ?? 1;
        $dataUpdate = [
            'name' => $data['name'],
            'username' => strtolower($data['username']),
            'avatar' => $data['avatar'],
            'email' => strtolower($data['email']),
        ];
        if ($data['password']) {
            $dataUpdate['password'] = bcrypt($data['password']);
        }
        AdminUser::updateInfo($dataUpdate, $id);

        if(!in_array($user->id, BC_GUARD_ADMIN)) {
            $roles = $data['roles'] ?? [];
            $permission = $data['permission'] ?? []; 
            $user->roles()->detach();
            $user->permissions()->detach();
            //Insert roles
            if ($roles) {
                $user->roles()->attach($roles);
            }
            //Insert permission
            if ($permission) {
                $user->permissions()->attach($permission);
            }

            //Update store
            $user->stores()->detach();
            $user->stores()->attach([$store]);

        }

//
        return redirect()->route('admin_user.index')->with('success', trans('user.admin.edit_success'));

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
            $arrID = array_diff($arrID, BC_GUARD_ADMIN);
            AdminUser::destroy($arrID);
            return response()->json(['error' => 1, 'msg' => '']);
        }
    }

}
