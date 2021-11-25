<?php

if (!function_exists('bc_captcha_method')) {
    function bc_captcha_method()
    {
        //If function captcha disable or dont setup
        if(empty(bc_config('captcha_mode'))) {
            return null;
        }

        // If method captcha selected
        if(!empty(bc_config('captcha_method'))) {
            $moduleClass = bc_config('captcha_method');
            //If class plugin captcha exist
            if(class_exists($moduleClass)) {
                //Check plugin captcha disable
                $key = (new $moduleClass)->configKey;
                if(bc_config($key)) {
                    return (new $moduleClass);
                } else {
                    return null;
                }
            }
        }
        return null;

    }
}

if (!function_exists('bc_captcha_page')) {
    function bc_captcha_page()
    {
        if(empty(bc_config('captcha_page'))) {
            return [];
        }

        if(!empty(bc_config('captcha_page'))) {
            return json_decode(bc_config('captcha_page'));
        }
    }
}

if (!function_exists('bc_get_plugin_captcha_installed')) {
    /**
     * Get all class plugin captcha installed
     *
     * @param   [string]  $code  Payment, Shipping
     *
     */
    function bc_get_plugin_captcha_installed($onlyActive = true)
    {
        $listPluginInstalled =  \BlackCart\Core\Admin\Models\AdminConfig::getPluginCaptchaCode($onlyActive);
        $arrPlugin = [];
        if($listPluginInstalled) {
            foreach ($listPluginInstalled as $key => $plugin) {
                $keyPlugin = bc_word_format_class($plugin->key);
                $pathPlugin = app_path() . '/Plugins/Other/'.$keyPlugin;
                $nameSpaceConfig = '\App\Plugins\Other\\'.$keyPlugin.'\AppConfig';
                if (file_exists($pathPlugin . '/AppConfig.php') && class_exists($nameSpaceConfig)) {
                    $arrPlugin[$nameSpaceConfig] = bc_language_render($plugin->detail);
                }
            }
        }
        return $arrPlugin;
    }
}