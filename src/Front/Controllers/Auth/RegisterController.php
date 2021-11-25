<?php

namespace BlackCart\Core\Front\Controllers\Auth;

use App\Http\Controllers\RootFrontController;
use BlackCart\Core\Front\Models\ShopEmailTemplate;
use BlackCart\Core\Front\Models\ShopCustomer;
use BlackCart\Core\Front\Models\ShopCountry;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use BlackCart\Core\Front\Controllers\Auth\AuthTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;

class RegisterController extends RootFrontController
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    use RegistersUsers;
    use AuthTrait;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = '/home';
    protected function redirectTo()
    {
        return bc_route('customer.index');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $dataMapping = $this->mappingValidator($data);
        if(bc_captcha_method() && in_array('register', bc_captcha_page())) {
            $data['captcha_field'] = $data[bc_captcha_method()->getField()] ?? '';
            $dataMapping['validate']['captcha_field'] = ['required', 'string', new \BlackCart\Core\Rules\CaptchaRule];
        }
        return Validator::make($data, $dataMapping['validate'], $dataMapping['messages']);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \BlackCart\Core\Front\Models\ShopCustomer
     */
    protected function create(array $data)
    {
        $data['country'] = strtoupper($data['country'] ?? '');
        $dataMap = $this->mappDataInsert($data);

        $user = ShopCustomer::createCustomer($dataMap);
        if ($user) {
            if (bc_config('welcome_customer')) {

                $checkContent = (new ShopEmailTemplate)->where('group', 'welcome_customer')->where('status', 1)->first();
                if ($checkContent) {
                    $content = $checkContent->text;
                    $dataFind = [
                        '/\{\{\$title\}\}/',
                        '/\{\{\$first_name\}\}/',
                        '/\{\{\$last_name\}\}/',
                        '/\{\{\$email\}\}/',
                        '/\{\{\$phone\}\}/',
                        '/\{\{\$password\}\}/',
                        '/\{\{\$address1\}\}/',
                        '/\{\{\$address2\}\}/',
                        '/\{\{\$address3\}\}/',
                        '/\{\{\$country\}\}/',
                    ];
                    $dataReplace = [
                        trans('email.welcome_customer.title'),
                        $dataMap['first_name'] ?? '',
                        $dataMap['last_name'] ?? '',
                        $dataMap['email'] ?? '',
                        $dataMap['phone'] ?? '',
                        $dataMap['password'] ?? '',
                        $dataMap['address1'] ?? '',
                        $dataMap['address2'] ?? '',
                        $dataMap['address3'] ?? '',
                        $dataMap['country'] ?? '',
                    ];
                    $content = preg_replace($dataFind, $dataReplace, $content);
                    $dataView = [
                        'content' => $content,
                    ];

                    $config = [
                        'to' => $data['email'],
                        'subject' => trans('email.welcome_customer.title'),
                    ];

                    bc_send_mail($this->templatePath . '.mail.welcome_customer', $dataView, $config, []);
                }

            }
        } else {

        }
        return $user;
    }
    
    public function showRegistrationForm()
    {
        return redirect(bc_route('register'));
        // return view('auth.register');
    }

    protected function registered(Request $request, $user)
    {
        redirect()->route('home')->with(['register_success' => trans('account.register_success')]);
    }


    /**
     * Process front form register
     *
     * @param [type] ...$params
     * @return void
     */
    public function showRegisterFormProcessFront(...$params) {
        if (config('app.seoLang')) {
            $lang = $params[0] ?? '';
            bc_lang_switch($lang);
        }
        return $this->_showRegisterForm();
    }


    /**
     * Form register
     *
     * @return  [type]  [return description]
     */
    private function _showRegisterForm()
    {
        if (session('customer')) {
            return redirect()->route('home');
        }
        $viewCaptcha = '';
        if(bc_captcha_method() && in_array('register', bc_captcha_page())) {
            if (view()->exists(bc_captcha_method()->pathPlugin.'::render')){
                $dataView = [
                    'titleButton' => trans('account.signup'),
                    'idForm' => 'form-process',
                    'idButtonForm' => 'button-form-process',
                ];
                $viewCaptcha = view(bc_captcha_method()->pathPlugin.'::render', $dataView)->render();
            }
        }
        bc_check_view($this->templatePath . '.Auth.register');
        return view($this->templatePath . '.Auth.register',
            array(
                'title'       => trans('account.title_register'),
                'countries'   => ShopCountry::getCodeAll(),
                'layout_page' => 'shop_auth',
                'viewCaptcha' => $viewCaptcha,
            )
        );
    }


    /**
     * Handle a registration request for the application.
     * User for Front
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return redirect(bc_route('home'))
                        ->withErrors($validator)
                        ->with(['register_state' => true]);
        }
        $data = $request->all();
        $user = $this->create($data);
        $dataMap = $this->mappDataInsert($data);
        if ($user) {
            if (bc_config('welcome_customer')) {

                $checkContent = (new ShopEmailTemplate)->where('group', 'welcome_customer')->where('status', 1)->first();
                if ($checkContent) {
                    $content = $checkContent->text;
                    $dataFind = [
                        '/\{\{\$title\}\}/',
                        '/\{\{\$first_name\}\}/',
                        '/\{\{\$last_name\}\}/',
                        '/\{\{\$email\}\}/',
                        '/\{\{\$phone\}\}/',
                        '/\{\{\$password\}\}/',
                        '/\{\{\$address1\}\}/',
                        '/\{\{\$address2\}\}/',
                        '/\{\{\$address3\}\}/',
                        '/\{\{\$country\}\}/',
                    ];
                    $dataReplace = [
                        trans('email.welcome_customer.title'),
                        $dataMap['first_name'] ?? '',
                        $dataMap['last_name'] ?? '',
                        $dataMap['email'] ?? '',
                        $dataMap['phone'] ?? '',
                        $dataMap['password'] ?? '',
                        $dataMap['address1'] ?? '',
                        $dataMap['address2'] ?? '',
                        $dataMap['address3'] ?? '',
                        $dataMap['country'] ?? '',
                    ];
                    $content = preg_replace($dataFind, $dataReplace, $content);
                    $dataView = [
                        'content' => $content,
                    ];

                    $config = [
                        'to' => $data['email'],
                        'subject' => trans('email.welcome_customer.title'),
                    ];

                    bc_send_mail($this->templatePath . '.mail.welcome_customer', $dataView, $config, []);
                }

            }

            //Send email verify
            $user->sendEmailVerify();

            //Login
            $this->guard()->login($user);
            
            if ($response = $this->registered($request, $user)) {
                return $response;
            }

        } else {
            return back()->withInput();
        }
        return $request->wantsJson()
                    ? new JsonResponse([], 201)
                    : redirect($this->redirectPath());
    }


}
