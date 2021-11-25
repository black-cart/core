<?php

namespace BlackCart\Core\Admin\Controllers;

use App\Http\Controllers\RootAdminController;
use BlackCart\Core\Admin\Models\AdminNews;
use BlackCart\Core\Admin\Models\AdminProduct;
use BlackCart\Core\Admin\Models\AdminCustomer;
use BlackCart\Core\Admin\Models\AdminOrder;
use Illuminate\Http\Request;

class DashboardController extends RootAdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index(Request $request)
    {
        // Check redirect dashboard multi-store
        if (function_exists('bc_vendor_redirect_dashboard')) {
            if (bc_vendor_redirect_dashboard()) {
                return redirect(bc_vendor_redirect_dashboard());
            }
        }

        //Check user allow view dasdboard
        if(!\Admin::user()->checkUrlAllowAccess(route('admin.home')))
        {
            $data['title'] = trans('admin.dashboard');
            return view($this->templatePathAdmin.'default', $data);
        }

        $data                   = [];
        $data['title']          = trans('admin.dashboard');
        $data['totalOrder']     = AdminOrder::getTotalOrder();
        $data['totalProduct']   = AdminProduct::getTotalProduct();
        $data['totalNews']      = AdminNews::getTotalNews();
        $data['totalCustomer']  = AdminCustomer::getTotalCustomer();
        $data['topCustomer']    = AdminCustomer::getTopCustomer();
        $data['topOrder']       = AdminOrder::getTopOrder();
        $data['mapStyleStatus'] = AdminOrder::$mapStyleStatus;

        //Country statistics
        $dataCountries = AdminOrder::getCountryInYear();
        $arrCountryMap   = [];
        foreach ($dataCountries as $key => $country) {
            if($key <= 3) {
                $countryName = $country->country ?? $key ;
                $arrCountryMap[] =  [
                    'name' => $countryName,
                    'order' => $country->count,
                    'amount' => $country->amount,
                    'currency' => $country->currency
                ];
            }
        }
        $data['dataCountries'] = json_encode($arrCountryMap);
        //End country statistics
        
        //Order in 1 week
        $totalsInWeek = AdminOrder::getSumOrderTotalInWeek()->keyBy('d')->toArray();
        $rangDays = new \DatePeriod(
            new \DateTime('-1 week'),
            new \DateInterval('P1D'),
            new \DateTime('+1 day')
        );
        $orderInWeek  = [];
        $amountInWeek  = [];
        foreach ($rangDays as $i => $day) {
            $date = $day->format('m-d');
            $orderInWeek[$date] = $totalsInWeek[$date]['total_order'] ?? '';
            $amountInWeek[$date] = ($totalsInWeek[$date]['total_amount'] ?? 0);
        }
        $data['orderInWeek'] = $orderInWeek;
        $data['amountInWeek'] = $amountInWeek;
        //End order in 1 week

        //Order in 30 days
        $totalsInMonth = AdminOrder::getSumOrderTotalInMonth()->keyBy('md')->toArray();
        $rangDays = new \DatePeriod(
            new \DateTime('-1 month'),
            new \DateInterval('P1D'),
            new \DateTime('+1 day')
        );
        $orderInMonth  = [];
        $amountInMonth  = [];
        foreach ($rangDays as $i => $day) {
            $date = $day->format('m-d');
            $orderInMonth[$date] = $totalsInMonth[$date]['total_order'] ?? '';
            $amountInMonth[$date] = ($totalsInMonth[$date]['total_amount'] ?? 0);
        }
        $data['orderInMonth'] = $orderInMonth;
        $data['amountInMonth'] = $amountInMonth;
        //End order in 30 days
        
        //Order in 12 months
        $totalsInYear = AdminOrder::getSumOrderTotalInYear()
            ->keyBy('ym')->toArray();
        $rangMonths = new \DatePeriod(
            new \DateTime('-12 month'),
            new \DateInterval('P1M'),
            new \DateTime('+1 month')
        );
        $orderInYear  = [];
        $amountInYear = [];
        foreach ($rangMonths as $i => $month) {
            $date = $month->format('Y-m');
            $orderInYear[$date] = $totalsInYear[$date]['total_order'] ?? '';
            $amountInYear[$date] = ($totalsInYear[$date]['total_amount'] ?? 0);
        }
        $data['orderInYear'] = $orderInYear;
        $data['amountInYear'] = $amountInYear;
        //End order in 12 months
        return view($this->templatePathAdmin.'Layout.dashboard', $data);
    }

    /**
     * Page not found
     *
     * @return  [type]  [return description]
     */
    public function dataNotFound()
    {
        $data = [
            'title' => trans('admin.data_not_found'),
            'icon' => '',
            'url' => session('url'),
        ];
        return view($this->templatePathAdmin.'Layout.data_not_found', $data);
    }


    /**
     * Page deny
     *
     * @return  [type]  [return description]
     */
    public function deny()
    {
        $data = [
            'title' => trans('admin.deny'),
            'icon' => '',
            'method' => session('method'),
            'url' => session('url'),
        ];
        return view($this->templatePathAdmin.'Layout.deny', $data);
    }

    /**
     * [denySingle description]
     *
     * @return  [type]  [return description]
     */
    public function denySingle()
    {
        $data = [
            'method' => session('method'),
            'url' => session('url'),
        ];
        return view($this->templatePathAdmin.'Layout.deny_single', $data);
    }
}
