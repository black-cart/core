<?php
namespace BlackCart\Core\Front\Controllers;

use App\Http\Controllers\RootFrontController;
use BlackCart\Core\Front\Models\ShopBanner;
use BlackCart\Core\Front\Models\ShopProduct;
use BlackCart\Core\Front\Models\ShopEmailTemplate;
use BlackCart\Core\Front\Models\ShopNews;
use BlackCart\Core\Front\Models\ShopPage;
use BlackCart\Core\Front\Models\ShopSubscribe;
use BlackCart\Core\Admin\Models\AdminCategory;
use Illuminate\Http\Request;

class ShopContentController extends RootFrontController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Home page
     * @return [view]
     */
    public function index()
    {
        bc_check_view($this->templatePath . '.Home.index');
        return view(
            $this->templatePath . '.Home.index',
            array(
                'title' => bc_store('title'),
                'keyword' => bc_store('keyword'),
                'description' => bc_store('description'),
                'layout_page' => 'home',
            )
        );
    }

    /**
     * Process front shop page
     *
     * @param [type] ...$params
     * @return void
     */
    public function shopProcessFront(...$params) 
    {
        if (config('app.seoLang')) {
            $lang = $params[0] ?? '';
            bc_lang_switch($lang);
        }
        return $this->_shop();
    }

    /**
     * Shop page
     * @return [view]
     */
    private function _shop()
    {
        $sortBy = 'sort';
        $sortOrder = 'asc';
        $filter_sort = request('filter_sort') ?? '';
        $filterArr = [
            'price_desc' => ['price', 'desc'],
            'price_asc' => ['price', 'asc'],
            'sort_desc' => ['sort', 'desc'],
            'sort_asc' => ['sort', 'asc'],
            'id_desc' => ['id', 'desc'],
            'id_asc' => ['id', 'asc'],
        ];
        if (array_key_exists($filter_sort, $filterArr)) {
            $sortBy = $filterArr[$filter_sort][0];
            $sortOrder = $filterArr[$filter_sort][1];
        }

        $products = (new ShopProduct)
            ->setLimit(bc_config('product_list'))
            ->setPaginate()
            ->setSort([$sortBy, $sortOrder])
            ->getData();

        bc_check_view($this->templatePath . '.Shop.index');
        return view(
            $this->templatePath . '.Shop.index',
            array(
                'title' => trans('front.shop'),
                'keyword' => bc_store('keyword'),
                'description' => bc_store('description'),
                'products' => $products,
                'layout_page' => 'shop_home',
                'filter_sort' => $filter_sort,
                'filterArr' => $filterArr
            )
        );
    }

    /**
     * Process front search page
     *
     * @param [type] ...$params
     * @return void
     */
    public function searchProcessFront(...$params) 
    {
        if (config('app.seoLang')) {
            $lang = $params[0] ?? '';
            bc_lang_switch($lang);
        }
        return $this->_search();
    }

    /**
     * search product
     * @return [view]
     */
    private function _search()
    {       
        $sortBy = 'sort';
        $sortOrder = 'asc';
        $filter_sort = request('filter_sort') ?? '';
        $filterArr = [
            'price_desc' => ['price', 'desc'],
            'price_asc' => ['price', 'asc'],
            'sort_desc' => ['sort', 'desc'],
            'sort_asc' => ['sort', 'asc'],
            'id_desc' => ['id', 'desc'],
            'id_asc' => ['id', 'asc'],
        ];
        if (array_key_exists($filter_sort, $filterArr)) {
            $sortBy = $filterArr[$filter_sort][0];
            $sortOrder = $filterArr[$filter_sort][1];
        }
        $keyword = request('keyword') ?? '';
        $products = (new ShopProduct)->setKeyword($keyword)
                    ->setSort([$sortBy, $sortOrder])
                    ->setPaginate()
                    ->setLimit(bc_config('product_list'))
                    ->getData();

        bc_check_view($this->templatePath . '.screen.shop_product_list');          
        return view(
            $this->templatePath . '.screen.shop_product_list',
            array(
                'title' => trans('front.search') . ': ' . $keyword,
                'products' => $products,
                'layout_page' => 'shop_product_list',
                'filter_sort' => $filter_sort,
            )
        );
    }

    /**
     * Process click banner
     *
     * @param   [int]  $id  
     *
     */
    public function clickBanner($id = 0) {
        $banner = ShopBanner::find($id);
        if ($banner) {
            $banner->click +=1;
            $banner->save();
            return redirect(url($banner->url??'/'));
        }
        return redirect(url('/'));
    }

    /**
     * Process front form contact page
     *
     * @param [type] ...$params
     * @return void
     */
    public function getContactProcessFront(...$params) 
    {
        if (config('app.seoLang')) {
            $lang = $params[0] ?? '';
            bc_lang_switch($lang);
        }
        return $this->_getContact();
    }

    /**
     * form contact
     * @return [view]
     */
    private function _getContact()
    {
        $viewCaptcha = '';
        if(bc_captcha_method() && in_array('contact', bc_captcha_page())) {
            if (view()->exists(bc_captcha_method()->pathPlugin.'::render')){
                $dataView = [
                    'titleButton' => trans('front.contact_form.submit'),
                    'idForm' => 'form-process',
                    'idButtonForm' => 'button-form-process',
                ];
                $viewCaptcha = view(bc_captcha_method()->pathPlugin.'::render', $dataView)->render();
            }
        }
        bc_check_view($this->templatePath . '.Shop.contact');
        return view(
            $this->templatePath . '.Shop.contact',
            array(
                'title'       => trans('front.contact'),
                'description' => '',
                'keyword'     => '',
                'layout_page' => 'shop_contact',
                'og_image'    => '',
                'viewCaptcha' => $viewCaptcha,
            )
        );
    }


    /**
     * process contact form
     * @param  Request $request [description]
     * @return [mix]
     */
    public function postContact(Request $request)
    {
        $data   = $request->all();
        $validate = [
            'name' => 'required',
            'title' => 'required',
            'content' => 'required',
            'email' => 'required|email',
            'phone' => 'required|regex:/^0[^0][0-9\-]{7,13}$/',
        ];
        $message = [
            'name.required'    => trans('validation.required', ['attribute' => trans('front.contact_form.name')]),
            'content.required' => trans('validation.required', ['attribute' => trans('front.contact_form.content')]),
            'title.required'   => trans('validation.required', ['attribute' => trans('front.contact_form.title')]),
            'email.required'   => trans('validation.required', ['attribute' => trans('front.contact_form.email')]),
            'email.email'      => trans('validation.email', ['attribute' => trans('front.contact_form.email')]),
            'phone.required'   => trans('validation.required', ['attribute' => trans('front.contact_form.phone')]),
            'phone.regex'      => trans('customer.phone_regex'),
        ];

        if(bc_captcha_method() && in_array('contact', bc_captcha_page())) {
            $data['captcha_field'] = $data[bc_captcha_method()->getField()] ?? '';
            $validate['captcha_field'] = ['required', 'string', new \BlackCart\Core\Rules\CaptchaRule];
        }
        $validator = \Illuminate\Support\Facades\Validator::make($data, $validate, $message);
        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }
        // Process escape
        $data = bc_clean($data);
        
        //Send email
        $data['content'] = str_replace("\n", "<br>", $data['content']);

        if (bc_config('contact_to_admin')) {
            $checkContent = (new ShopEmailTemplate)
                ->where('group', 'contact_to_admin')
                ->where('status', 1)
                ->first();
            if ($checkContent) {
                $content = $checkContent->text;
                $dataFind = [
                    '/\{\{\$title\}\}/',
                    '/\{\{\$name\}\}/',
                    '/\{\{\$email\}\}/',
                    '/\{\{\$phone\}\}/',
                    '/\{\{\$content\}\}/',
                ];
                $dataReplace = [
                    $data['title'],
                    $data['name'],
                    $data['email'],
                    $data['phone'],
                    $data['content'],
                ];
                $content = preg_replace($dataFind, $dataReplace, $content);
                $dataView = [
                    'content' => $content,
                ];

                $config = [
                    'to' => bc_store('email'),
                    'replyTo' => $data['email'],
                    'subject' => $data['title'],
                ];
                bc_send_mail($this->templatePath . '.Mail.contact_to_admin', $dataView, $config, []);
            }
        }

        return redirect(bc_route('contact'))
            ->with('success', trans('front.thank_contact'));
    }

    /**
     * Process front form page detail
     *
     * @param [type] ...$params
     * @return void
     */
    public function pageDetailProcessFront(...$params) 
    {
        if (config('app.seoLang')) {
            $lang = $params[0] ?? '';
            $alias = $params[1] ?? '';
            bc_lang_switch($lang);
        } else {
            $alias = $params[0] ?? '';
        }
        return $this->_pageDetail($alias);
    }

    /**
     * Render page
     * @param  [string] $alias
     */
    private function _pageDetail($alias)
    {
        $page = (new ShopPage)->getDetail($alias, $type = 'alias');
        if ($page) {

            bc_check_view($this->templatePath . '.Shop.aboutus');
            return view(
                $this->templatePath . '.Shop.aboutus',
                array(
                    'title' => $page->title,
                    'description' => $page->description,
                    'keyword' => $page->keyword,
                    'page' => $page,
                    'og_image' => asset($page->getImage()),
                    'layout_page' => 'about_us',
                )
            );
        } else {
            return $this->pageNotFound();
        }
    }

    /**
     * Process front news
     *
     * @param [type] ...$params
     * @return void
     */
    public function newsProcessFront(...$params) 
    {
        if (config('app.seoLang')) {
            $lang = $params[0] ?? '';
            bc_lang_switch($lang);
        }
        return $this->_news();
    }

    /**
     * Render news
     * @return [type] [description]
     */
    private function _news()
    {
        $news = (new ShopNews)
            ->setLimit(bc_config('news_list'))
            ->setPaginate()
            ->getData();

        bc_check_view($this->templatePath . '.News.index');
        return view(
            $this->templatePath . '.News.index',
            array(
                'title' => trans('front.blog'),
                'description' => bc_store('description'),
                'keyword' => bc_store('keyword'),
                'news' => $news,
                'layout_page' => 'news',
            )
        );
    }

    /**
     * Process front news detail
     *
     * @param [type] ...$params
     * @return void
     */
    public function newsDetailProcessFront(...$params) 
    {
        if (config('app.seoLang')) {
            $lang = $params[0] ?? '';
            $alias = $params[1] ?? '';
            bc_lang_switch($lang);
        } else {
            $alias = $params[0] ?? '';
        }
        return $this->_newsDetail($alias);
    }

    /**
     * News detail
     *
     * @param   [string]  $alias 
     *
     * @return  view
     */
    private function _newsDetail($alias)
    {
        $news = (new ShopNews)->getDetail($alias, $type ='alias');
        if ($news) {
            bc_check_view($this->templatePath . '.News.detail');
            return view(
                $this->templatePath . '.News.detail',
                array(
                    'title' => $news->title,
                    'news' => $news,
                    'description' => $news->description,
                    'keyword' => $news->keyword,
                    'og_image' => asset($news->getImage()),
                    'layout_page' => 'news_detail',
                )
            );
        } else {
            return $this->pageNotFound();
        }
    }

    /**
     * email subscribe
     * @param  Request $request
     * @return json
     */
    public function emailSubscribe(Request $request)
    {
        $validator = $request->validate([
            'subscribe_email' => 'required|email',
            ], [
            'email.required' => trans('validation.required'),
            'email.email'    => trans('validation.email'),
        ]);
        $data       = $request->all();
        $checkEmail = ShopSubscribe::where('email', $data['subscribe_email'])
            ->first();
        if (!$checkEmail) {
            ShopSubscribe::insert(['email' => $data['subscribe_email']]);
        }
        return redirect()->back()
            ->with(['success' => trans('subscribe.subscribe_success')]);
    }

}
