<?php
namespace BlackCart\Core\Admin\Controllers;

use App\Http\Controllers\RootAdminController;

class AdminSeoConfigController extends RootAdminController
{

    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {

        $data = [
            'title'    => trans('seo.seo_config'),
            'subTitle' => '',
            'icon'     => 'fa fa-indent',
        ];
        $data['urlUpdateConfigGlobal'] = bc_route_admin('admin_config_global.update');
        return view($this->templatePathAdmin.'Seo.config')
            ->with($data);
    }

}
