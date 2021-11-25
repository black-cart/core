<?php
$prefixProduct = bc_config('PREFIX_PRODUCT')??'product';

Route::group(['prefix' => $langUrl.$prefixProduct], function ($router) use ($suffix) {
    $router->get('/', 'ShopProductController@allProductsProcessFront')
        ->name('product.all');
    $router->get('/{alias}/s{storeId}'.$suffix, 'ShopProductController@productDetailProcessFront')
        ->name('product.detail');
    // quickview
    $router->post('/quick_view', 'ShopProductController@productDetailQuickViewProcess')
        ->name('product.quickview');
});