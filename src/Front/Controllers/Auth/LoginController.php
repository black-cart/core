<?php

namespace BlackCart\Core\Front\Controllers\Auth;

use App\Http\Controllers\RootFrontController;
use BlackCart\Core\Front\Models\ShopCountry;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends RootFrontController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/';
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
        $this->middleware('guest')->except('logout');
    }

    protected function validateLogin(Request $request)
    {
        $messages = [
            'email.email'       => trans('validation.email',['attribute'=> trans('customer.email')]),
            'email.required'    => trans('validation.required',['attribute'=> trans('customer.email')]),
            'password.required' => trans('validation.required',['attribute'=> trans('customer.password')]),
            ];
        $this->validate($request, [
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ], $messages);
    }

    /**
     * Process front form login
     *
     * @param [type] ...$params
     * @return void
     */
    public function showLoginFormProcessFront(...$params) {
        if (config('app.seoLang')) {
            $lang = $params[0] ?? '';
            bc_lang_switch($lang);
        }
        return $this->_showLoginForm();
    }


    /**
     * Form login
     *
     * @return  [type]  [return description]
     */
    private function _showLoginForm()
    {
        if (Auth::user()) {
            return redirect()->route('home');
        }
        bc_check_view($this->templatePath . '.auth.login');
        return view($this->templatePath . '.auth.login',
            array(
                'title'       => trans('front.login'),
                'countries'   => ShopCountry::getCodeAll(),
                'layout_page' => 'shop_auth',
            )
        );
    }


    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect(bc_route('home'))->with('login_form', 'true');
    }

    protected function authenticated(Request $request, $user)
    {
        if (auth()->user()) {
            session(['customer' => auth()->user()->toJson()]);
        } else {
            session(['customer' => []]);
        }
    }

}
