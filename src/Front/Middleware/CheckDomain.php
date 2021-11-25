<?php

namespace BlackCart\Core\Front\Middleware;

use Closure;
use BlackCart\Core\Front\Models\ShopStore;

class CheckDomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (bc_config_global('MultiVendorPro') || bc_config_global('MultiStorePro')) {
            //Check domain exist
            $domain = bc_process_domain_store(url('/'));
            $arrDomain = ShopStore::getDomainPartner();
            if (!in_array($domain, $arrDomain) && bc_config_global('domain_strict') && config('app.storeId') != BC_ID_ROOT) {
                echo view('deny_domain')->render();
                exit();
            }
        }
        return $next($request);
    }
}
