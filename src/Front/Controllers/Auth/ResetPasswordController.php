<?php

namespace BlackCart\Core\Front\Controllers\Auth;

use App\Http\Controllers\RootFrontController;
use Auth;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules;
class ResetPasswordController extends RootFrontController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
     */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    // protected $redirectTo = '/home';
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct(); //
        $this->middleware('guest');
    }

    /**
     * Process front Form forgot password
     *
     * @param [type] ...$params
     * @return void
     */
    public function showResetFormProcessFront(...$params) {
        if (config('app.seoLang')) {
            $lang = $params[0] ?? '';
            $token = $params[1] ?? '';
            bc_lang_switch($lang);
        } else {
            $token = $params[0] ?? '';
        }
        return $this->_showResetForm($token);
    }

    /**
     * Form reset password
     *
     * @param   Request  $request
     * @param   [string]   $token
     *
     * @return  [view]
     */
    private function _showResetForm($token = null)
    {
        if (Auth::user()) {
            return redirect()->route('home');
        }
        return redirect()->route('home')->with(['token' => $token,'reset_state' => true]);
    }
    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        $validate = [
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
        $dataMap = [
            'validate' => $validate,
            'messages' => [                
                'token.required'       => trans('validation.required', ['attribute'=> 'token']),
                'email.required'       => trans('validation.required', ['attribute'=> trans('customer.email')]),
                'email.email'          => trans('validation.email'),
                'password.required'    => trans('validation.required', ['attribute'=> trans('customer.password')]),
                'password.confirmed'   => trans('validation.confirmed',['attribute' => trans('customer.password')]),
            ]
        ];
        return $dataMap;
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $dataMapping = $this->rules();
        if(bc_captcha_method() && in_array('forgot', bc_captcha_page())) {
            $data['captcha_field'] = $data[bc_captcha_method()->getField()] ?? '';
            $dataMapping['validate']['captcha_field'] = ['required', 'string', new \BlackCart\Core\Rules\CaptchaRule];
        }
        return Validator::make($data, $dataMapping['validate'], $dataMapping['messages']);
    }
    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return redirect()->route('home')
                        ->withErrors($validator)
                        ->with(['token' => $request->token,'reset_failed' => true]);
        }
        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );
        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response == Password::PASSWORD_RESET
                    ? redirect()->route('home')->with(['reset_success' => true])
                    : redirect()->route('home')->with(['token' => $request->token,'reset_failed' => true])->withErrors(['email'=>'Email không khớp!']);
    }
}
