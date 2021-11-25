<?php

namespace BlackCart\Core;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use BlackCart\Core\Front\Models\ShopProduct;
use BlackCart\Core\Front\Models\ShopCategory;
use BlackCart\Core\Front\Models\ShopBanner;
use BlackCart\Core\Front\Models\ShopBrand;
use BlackCart\Core\Front\Models\ShopNews;
use BlackCart\Core\Front\Models\ShopPage;
use BlackCart\Core\Front\Models\ShopStore;
use BlackCart\Core\Front\Models\ShopCountry;
use BlackCart\Core\Front\Models\ShopAttributeGroup;
use BlackCart\Core\Commands\Customize;
use BlackCart\Core\Commands\Backup;
use BlackCart\Core\Commands\Restore;
use BlackCart\Core\Commands\MakePlugin;
use BlackCart\Core\Commands\Infomation;
class BlackCartServiceProvider extends ServiceProvider
{
    protected $commands = [
        Customize::class,
        Backup::class,
        Restore::class,
        MakePlugin::class,
        Infomation::class,
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/Config/admin.php', 'admin');
        $this->mergeConfigFrom(__DIR__.'/Config/validation.php', 'validation');
        $this->mergeConfigFrom(__DIR__.'/Config/lfm.php', 'lfm');
        $this->mergeConfigFrom(__DIR__.'/Config/black-cart.php', 'black-cart');
        $this->loadViewsFrom(__DIR__.'/Admin/Views', 'black-cart');

        $this->registerPublishing();
        
        if (!file_exists(public_path('install.php')) && file_exists(base_path('.env'))) {
            foreach (glob(__DIR__.'/Library/Helpers/*.php') as $filename) {
                require_once $filename;
            }
            foreach (glob(app_path() . '/Library/Helpers/*.php') as $filename) {
                require_once $filename;
            }

            foreach (glob(app_path() . '/Plugins/*/*/Provider.php') as $filename) {
                require_once $filename;
            }

            $this->bootBlackCart();

            //Route Admin
            if (file_exists($routes = __DIR__.'/Admin/routes.php')) {
                $this->loadRoutesFrom($routes);
            }

            //Route Api
            if (file_exists($routes = __DIR__.'/Api/routes.php')) {
                $this->loadRoutesFrom($routes);
            }

            //Route Front
            if (file_exists($routes = __DIR__.'/Front/routes.php')) {
                $this->loadRoutesFrom($routes);
            }

            try {
                DB::connection(BC_CONNECTION)->getPdo();
            } catch(\Throwable $e) {
                bc_report($e->getMessage());
                return;
            }
        }

        $this->validationExtend();

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        if (file_exists(__DIR__.'/Library/Const.php')) {
            require_once (__DIR__.'/Library/Const.php');
        }
        $this->app->bind('cart', '\BlackCart\Core\Library\ShoppingCart\Cart');
        
        $this->registerRouteMiddleware();

        $this->commands($this->commands);
    }

    public function bootBlackCart()
    {
        // Set store id
        // Default is domain root
        $storeId = BC_ID_ROOT;

        //Process for multi store
        if(bc_config_global('MultiVendorPro') || bc_config_global('MultiStorePro')) {
            $domain = bc_process_domain_store(url('/'));
            $arrDomain = ShopStore::getDomainPartner();
            if (in_array($domain, $arrDomain)) {
                $storeId =  array_search($domain, $arrDomain);
            }
        }
        //End process multi store

        config(['app.storeId' => $storeId]);
        // end set store Id

        if (bc_config_global('LOG_SLACK_WEBHOOK_URL')) {
            config(['logging.channels.slack.url' => bc_config_global('LOG_SLACK_WEBHOOK_URL')]);
        }

        //Config language url
        config(['app.seoLang' => (bc_config_global('url_seo_lang') ? '{lang?}/' : '')]);

        //Title app
        config(['app.name' => bc_store('title')]);

        //Config for  email
        if (
            // Default use smtp mode for for supplier if use multi-store
            ($storeId != BC_ID_ROOT && (bc_config_global('MultiVendorPro') || bc_config_global('MultiStorePro')))
            ||
            // Use smtp config from admin if root domain have smtp_mode enable
            ($storeId == BC_ID_ROOT && bc_config_global('smtp_mode'))
            ) {
            $smtpHost     = bc_config('smtp_host');
            $smtpPort     = bc_config('smtp_port');
            $smtpSecurity = bc_config('smtp_security');
            $smtpUser     = bc_config('smtp_user');
            $smtpPassword = bc_config('smtp_password');
            $smtpName     = bc_config('smtp_name');
            $smtpFrom     = bc_config('smtp_from');
            config(['mail.default'                 => 'smtp']);
            config(['mail.mailers.smtp.host'       => $smtpHost]);
            config(['mail.mailers.smtp.port'       => $smtpPort]);
            config(['mail.mailers.smtp.encryption' => $smtpSecurity]);
            config(['mail.mailers.smtp.username'   => $smtpUser]);
            config(['mail.mailers.smtp.password' => $smtpPassword]);
            config(['mail.from.address' => ($smtpFrom ?? bc_store('email'))]);
            config(['mail.from.name' => ($smtpName ?? bc_store('title'))]);
        }
        //email

        // Time zone
        config(['app.timezone' => (bc_store('timezone') ?? config('app.timezone'))]);
        // End time zone

        //Share variable for view
        view()->share('bc_languages', bc_language_all());
        view()->share('bc_currencies', bc_currency_all());
        view()->share('bc_blocksContent', bc_store_block());
        view()->share('bc_layoutsUrl', bc_link());
        view()->share('bc_templatePath', 'templates.' . bc_store('template'));
        view()->share('bc_templateFile', 'templates/' . bc_store('template'));
        view()->share('bc_countriesList', (new ShopCountry)->getCodeAll());
        //variable model
        view()->share('modelProduct', (new ShopProduct));
        view()->share('modelCategory', (new ShopCategory));
        view()->share('modelBanner', (new ShopBanner));
        view()->share('modelBrand', (new ShopBrand));
        view()->share('modelNews', (new ShopNews));
        view()->share('modelPage', (new ShopPage));
        view()->share('modelAttributesGroup', (new ShopAttributeGroup));
        //
        view()->share('templatePathAdmin', (config('admin.customize') ? 'admin.': 'black-cart::'));


    }

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'localization'     => Front\Middleware\Localization::class,
        'email.verify'     => Front\Middleware\EmailIsVerified::class,
        'currency'         => Front\Middleware\Currency::class,
        'api.connection'   => Api\Middleware\ApiConnection::class,
        'checkdomain'      => Front\Middleware\CheckDomain::class,
        'json.response'    => Api\Middleware\ForceJsonResponse::class,
        //Admin
        'admin.auth'       => Admin\Middleware\Authenticate::class,
        'admin.log'        => Admin\Middleware\LogOperation::class,
        'admin.permission' => Admin\Middleware\PermissionMiddleware::class,
        'admin.storeId'    => Admin\Middleware\AdminStoreId::class,
        'admin.theme'      => Admin\Middleware\AdminTheme::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'admin' => [
            'admin.auth',
            'admin.permission',
            'admin.log',
            'admin.storeId',
            'admin.theme',
            'localization',
        ],
        'front' => [
            'localization',
            'currency',
            'checkdomain',
        ],
        'api.extent' => [
            'json.response',
            'api.connection',
            'throttle:1000',
        ],
    ];

    /**
     * Register the route middleware.
     *
     * @return void
     */
    protected function registerRouteMiddleware()
    {
        // register route middleware.
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }

        // register middleware group.
        foreach ($this->middlewareGroups as $key => $middleware) {
            app('router')->middlewareGroup($key, $middleware);
        }
    }


    /**
     * Validattion extend
     *
     * @return  [type]  [return description]
     */
    protected function validationExtend() {
        Validator::extend('product_sku_unique', function ($attribute, $value, $parameters, $validator) {
            $productId = $parameters[0] ?? '';
            return (new Admin\Models\AdminProduct)
                ->checkProductValidationAdmin('sku', $value, $productId, session('adminStoreId'));
        });

        Validator::extend('product_alias_unique', function ($attribute, $value, $parameters, $validator) {
            $productId = $parameters[0] ?? '';
            return (new Admin\Models\AdminProduct)
                ->checkProductValidationAdmin('alias', $value, $productId, session('adminStoreId'));
        });

    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/Admin/Views'  => resource_path('views/admin')], 'sc:view-admin');
            $this->publishes([__DIR__.'/Config/admin.php' => config_path('admin.php')], 'sc:config-admin');
            $this->publishes([__DIR__.'/Config/validation.php' => config_path('validation.php')], 'sc:config-validation');
            $this->publishes([__DIR__.'/Publishing/database' => base_path('database')], 'sc:migrate-database');
        }
    }
}
