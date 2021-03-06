<?php
namespace BlackCart\Core\Admin\Controllers;

use App\Http\Controllers\RootAdminController;
use BlackCart\Core\Admin\Models\AdminConfig;

class AdminConfigGlobalController extends RootAdminController
{
    public $templates, $currencies, $languages, $timezones;

    public function __construct()
    {
        parent::__construct();
    }

    public function webhook()
    {
        $data = [
            'title' => trans('config.admin.webhook'),
            'subTitle' => '',
            'icon' => 'fa fa-indent',  
        ];
        return view($this->templatePathAdmin.'screen.webhook')
            ->with($data);
    }

    /**
     * Update config global
     *
     * @return  [type]  [return description]
     */
    public function update() {
        $data = request()->all();
        $name = $data['name'];
        $value = $data['value'];
        try {
            AdminConfig::where('key', $name)
                ->where('store_id', 0)
                ->update(['value' => $value]);
            $error = 0;
            $msg = trans('admin.update_success');
        } catch (\Throwable $e) {
            $error = 1;
            $msg = $e->getMessage();
        }
        return response()->json([
            'error' => $error,
            'field' => $name,
            'value' => $value,
            'msg'   => $msg,
            ]
        );
    }

}
