<?php
// Auth::routes();
$prefixCustomer = bc_config('PREFIX_MEMBER') ?? 'customer';
$langUrl = config('app.seoLang');
//--Auth
Route::group(
    [
        'namespace' => 'Auth', 
        'prefix' => $langUrl.$prefixCustomer,
    ],
    function ($router) use ($suffix) {
        $router->get('/login'.$suffix, 'LoginController@showLoginFormProcessFront')
            ->name('login');
        $router->post('/login'.$suffix, 'LoginController@login')
            ->name('postLogin');

        $router->get('/register'.$suffix, 'RegisterController@showRegisterFormProcessFront')
            ->name('register');
        $router->post('/register'.$suffix, 'RegisterController@register')
            ->name('postRegister');

        $router->post('/logout', 'LoginController@logout')
            ->name('logout');

        $router->get('/forgot'.$suffix, 'ForgotPasswordController@showLinkRequestFormProcessFront')
            ->name('forgot');

        $router->get('/password/verify/{token}', 'ResetPasswordController@showResetFormProcessFront')
            ->name('password.verify');
        $router->post('/password/email', 'ForgotPasswordController@sendResetLinkEmail')
            ->name('password.email');
        $router->post('/password/reset', 'ResetPasswordController@reset')
        ->name('password.reset');
    }
);

if ($suffix) {
    Route::get('/login', function () {
        return redirect(bc_route('login'));
    });
}

