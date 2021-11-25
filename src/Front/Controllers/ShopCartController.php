<?php
namespace BlackCart\Core\Front\Controllers;

use App\Http\Controllers\RootFrontController;
use BlackCart\Core\Front\Models\ShopEmailTemplate;
use BlackCart\Core\Front\Models\ShopAttributeGroup;
use BlackCart\Core\Front\Models\ShopCountry;
use BlackCart\Core\Front\Models\ShopOrder;
use BlackCart\Core\Front\Models\ShopOrderTotal;
use BlackCart\Core\Front\Models\ShopProduct;
use BlackCart\Core\Front\Models\ShopCustomer;
use BlackCart\Core\Front\Models\ShopCustomerAddress;
use Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopCartController extends RootFrontController
{
    const ORDER_STATUS_NEW = 1;
    const PAYMENT_UNPAID = 1;
    const SHIPPING_NOTSEND = 1;

    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Process front get cart
     *
     * @param [type] ...$params
     * @return void
     */
    public function getCartProcessFront(...$params) 
    {
        if (config('app.seoLang')) {
            $lang = $params[0] ?? '';
            bc_lang_switch($lang);
        }
        return $this->_getCart();
    }

    /**
     * Get list cart: screen get cart
     * @return [type] [description]
     */
    private function _getCart()
    {
        session()->forget('paymentMethod'); //destroy paymentMethod
        session()->forget('shippingMethod'); //destroy shippingMethod
        session()->forget('orderID'); //destroy orderID
        
        //Shipping
        $moduleShipping = bc_get_plugin_installed('shipping');
        $sourcesShipping = bc_get_all_plugin('shipping');
        $shippingMethod = array();
        foreach ($moduleShipping as $module) {
            if (array_key_exists($module['key'], $sourcesShipping)) {
                $moduleClass = bc_get_class_plugin_config('shipping', $module['key']);
                $shippingMethod[$module['key']] = (new $moduleClass)->getData();
            }
        }

        //Payment
        $modulePayment = bc_get_plugin_installed('payment');
        $sourcesPayment = bc_get_all_plugin('payment');
        $paymentMethod = array();
        foreach ($modulePayment as $module) {
            if (array_key_exists($module['key'], $sourcesPayment)) {
                $moduleClass = $sourcesPayment[$module['key']].'\AppConfig';
                $paymentMethod[$module['key']] = (new $moduleClass)->getData();
            }
        }        

        //Total
        $moduleTotal = bc_get_plugin_installed('total');
        $sourcesTotal = bc_get_all_plugin('total');
        $totalMethod = array();
        foreach ($moduleTotal as $module) {
            if (array_key_exists($module['key'], $sourcesTotal)) {
                $moduleClass = $sourcesTotal[$module['key']].'\AppConfig';
                $totalMethod[$module['key']] = (new $moduleClass)->getData();
            }
        } 

        // Shipping address
        $customer = auth()->user();
        if ($customer) {
            $address = $customer->getAddressDefault();
            if ($address) {
                $addressDefaul = [
                    'first_name'      => $address->first_name,
                    'last_name'       => $address->last_name,
                    'first_name_kana' => $address->first_name_kana,
                    'last_name_kana'  => $address->last_name_kana,
                    'email'           => $customer->email,
                    'address1'        => $address->address1,
                    'address2'        => $address->address2,
                    'address3'        => $address->address3,
                    'postcode'        => $address->postcode,
                    'company'         => $customer->company,
                    'country'         => $address->country,
                    'phone'           => $address->phone,
                    'comment'         => '',
                ];
            } else {
                $addressDefaul = [
                    'first_name'      => $customer->first_name,
                    'last_name'       => $customer->last_name,
                    'first_name_kana' => $customer->first_name_kana,
                    'last_name_kana'  => $customer->last_name_kana,
                    'email'           => $customer->email,
                    'address1'        => $customer->address1,
                    'address2'        => $customer->address2,
                    'address3'        => $customer->address3,
                    'postcode'        => $customer->postcode,
                    'company'         => $customer->company,
                    'country'         => $customer->country,
                    'phone'           => $customer->phone,
                    'comment'         => '',
                ];
            }

        } else {
            $addressDefaul = [
                'first_name'      => '',
                'last_name'       => '',
                'first_name_kana' => '',
                'last_name_kana'  => '',
                'postcode'        => '',
                'company'         => '',
                'email'           => '',
                'address1'        => '',
                'address2'        => '',
                'address3'        => '',
                'country'         => '',
                'phone'           => '',
                'comment'         => '',
            ];
        }
        $shippingAddress = session('shippingAddress') ?? $addressDefaul;
        $objects = ShopOrderTotal::getObjectOrderTotal();
        $viewCaptcha = '';
        if(bc_captcha_method() && in_array('checkout', bc_captcha_page())) {
            if (view()->exists(bc_captcha_method()->pathPlugin.'::render')){
                $dataView = [
                    'titleButton' => trans('cart.checkout'),
                    'idForm' => 'form-process',
                    'idButtonForm' => 'button-form-process',
                ];
                $viewCaptcha = view(bc_captcha_method()->pathPlugin.'::render', $dataView)->render();
            }
        }

        bc_check_view($this->templatePath . '.Cart.index');
        return view(
            $this->templatePath . '.Cart.index',
            [
                'title'           => trans('front.cart_title'),
                'description'     => '',
                'keyword'         => '',
                'cart'            => Cart::instance('default')->content(),
                'shippingMethod'  => $shippingMethod,
                'paymentMethod'   => $paymentMethod,
                'totalMethod'     => $totalMethod,
                'addressList'     => $customer ? $customer->addresses : [],
                'dataTotal'       => ShopOrderTotal::processDataTotal($objects),
                'shippingAddress' => $shippingAddress,
                'countries'       => ShopCountry::getCodeAll(),
                'attributesGroup' => ShopAttributeGroup::pluck('name', 'id')->all(),
                'viewCaptcha'     => $viewCaptcha,
                'layout_page'     => 'Cart',
            ]
        );
    }

    /**
     * Process front prepare checkout
     *
     * @param [type] ...$params
     * @return void
     */
    public function checkoutPrepareProcessFront(...$params) 
    {
        if (config('app.seoLang')) {
            $lang = $params[0] ?? '';
            bc_lang_switch($lang);
        }
        return $this->_checkoutPrepare();
    }

    /**
     * Process Cart, prepare for the checkout screen
     */
    private function _checkoutPrepare()
    {
        $customer = auth()->user();
        if (Cart::instance('default')->count() == 0) {
            return redirect(bc_route('cart'));
        }

        //Not allow for guest
        if (!bc_config('shop_allow_guest') && !$customer) {
            return redirect(bc_route('login'));
        }

        $data = request()->all();

        $validate = [
            'first_name'     => config('validation.customer.first_name', 'required|string|max:100'),
            'email'          => config('validation.customer.email', 'required|string|email|max:255'),
        ];
        //check shipping
        if (!bc_config('shipping_off')) {
            $validate['shippingMethod'] = 'required';
        }
        //check payment
        if (!bc_config('payment_off')) {
            $validate['paymentMethod'] = 'required';
        }

        if (bc_config('customer_lastname')) {
            if (bc_config('customer_lastname_required')) {
                $validate['last_name'] = config('validation.customer.last_name_required', 'required|string|max:100');
            } else {
                $validate['last_name'] = config('validation.customer.last_name_null', 'nullable|string|max:100');
            }
        }
        if (bc_config('customer_address1')) {
            if (bc_config('customer_address1_required')) {
                $validate['address1'] = config('validation.customer.address1_required', 'required|string|max:100');
            } else {
                $validate['address1'] = config('validation.customer.address1_null', 'nullable|string|max:100');
            }
        }

        if (bc_config('customer_address2')) {
            if (bc_config('customer_address2_required')) {
                $validate['address2'] = config('validation.customer.address2_required', 'required|string|max:100');
            } else {
                $validate['address2'] = config('validation.customer.address2_null', 'nullable|string|max:100');
            }
        }

        if (bc_config('customer_address3')) {
            if (bc_config('customer_address3_required')) {
                $validate['address3'] = config('validation.customer.address3_required', 'required|string|max:100');
            } else {
                $validate['address3'] = config('validation.customer.address3_null', 'nullable|string|max:100');
            }
        }

        if (bc_config('customer_phone')) {
            if (bc_config('customer_phone_required')) {
                $validate['phone'] = config('validation.customer.phone_required', 'required|regex:/^0[^0][0-9\-]{7,13}$/');
            } else {
                $validate['phone'] = config('validation.customer.phone_null', 'nullable|regex:/^0[^0][0-9\-]{7,13}$/');
            }
        }
        if (bc_config('customer_country')) {
            $arraycountry = (new ShopCountry)->pluck('code')->toArray();
            if (bc_config('customer_country_required')) {
                $validate['country'] = config('validation.customer.country_required', 'required|string|min:2').'|in:'. implode(',', $arraycountry);
            } else {
                $validate['country'] = config('validation.customer.country_null', 'nullable|string|min:2').'|in:'. implode(',', $arraycountry);
            }
        }

        if (bc_config('customer_postcode')) {
            if (bc_config('customer_postcode_required')) {
                $validate['postcode'] = config('validation.customer.postcode_required', 'required|min:5');
            } else {
                $validate['postcode'] = config('validation.customer.postcode_null', 'nullable|min:5');
            }
        }
        if (bc_config('customer_company')) {
            if (bc_config('customer_company_required')) {
                $validate['company'] = config('validation.customer.company_required', 'required|string|max:100');
            } else {
                $validate['company'] = config('validation.customer.company_null', 'nullable|string|max:100');
            }
        } 

        if (bc_config('customer_name_kana')) {
            if (bc_config('customer_name_kana_required')) {
                $validate['first_name_kana'] = config('validation.customer.name_kana_required', 'required|string|max:100');
                $validate['last_name_kana'] = config('validation.customer.name_kana_required', 'required|string|max:100');
            } else {
                $validate['first_name_kana'] = config('validation.customer.name_kana_null', 'nullable|string|max:100');
                $validate['last_name_kana'] = config('validation.customer.name_kana_null', 'nullable|string|max:100');
            }
        }

        $messages = [
            'last_name.required'      => trans('validation.required', ['attribute'=> trans('cart.last_name')]),
            'first_name.required'     => trans('validation.required', ['attribute'=> trans('cart.first_name')]),
            'email.required'          => trans('validation.required', ['attribute'=> trans('cart.email')]),
            'address1.required'       => trans('validation.required', ['attribute'=> trans('cart.address1')]),
            'address2.required'       => trans('validation.required', ['attribute'=> trans('cart.address2')]),
            'address3.required'       => trans('validation.required', ['attribute'=> trans('cart.address3')]),
            'phone.required'          => trans('validation.required', ['attribute'=> trans('cart.phone')]),
            'country.required'        => trans('validation.required', ['attribute'=> trans('cart.country')]),
            'postcode.required'       => trans('validation.required', ['attribute'=> trans('cart.postcode')]),
            'company.required'        => trans('validation.required', ['attribute'=> trans('cart.company')]),
            'sex.required'            => trans('validation.required', ['attribute'=> trans('cart.sex')]),
            'birthday.required'       => trans('validation.required', ['attribute'=> trans('cart.birthday')]),
            'email.email'             => trans('validation.email', ['attribute'=> trans('cart.email')]),
            'phone.regex'             => trans('customer.phone_regex'),
            'postcode.min'            => trans('validation.min', ['attribute'=> trans('cart.postcode')]),
            'country.min'             => trans('validation.min', ['attribute'=> trans('cart.country')]),
            'first_name.max'          => trans('validation.max', ['attribute'=> trans('cart.first_name')]),
            'email.max'               => trans('validation.max', ['attribute'=> trans('cart.email')]),
            'address1.max'            => trans('validation.max', ['attribute'=> trans('cart.address1')]),
            'address2.max'            => trans('validation.max', ['attribute'=> trans('cart.address2')]),
            'address3.max'            => trans('validation.max', ['attribute'=> trans('cart.address3')]),
            'last_name.max'           => trans('validation.max', ['attribute'=> trans('cart.last_name')]),
            'birthday.date'           => trans('validation.date', ['attribute'=> trans('cart.birthday')]),
            'birthday.date_format'    => trans('validation.date_format', ['attribute'=> trans('cart.birthday')]),
            'shippingMethod.required' => trans('cart.validation.shippingMethod_required'),
            'paymentMethod.required'  => trans('cart.validation.paymentMethod_required'),
        ];

        if(bc_captcha_method() && in_array('checkout', bc_captcha_page())) {
            $data['captcha_field'] = $data[bc_captcha_method()->getField()] ?? '';
            $validate['captcha_field'] = ['required', 'string', new \BlackCart\Core\Rules\CaptchaRule];
        }


        $v = Validator::make(
            $data, 
            $validate, 
            $messages
        );
        if ($v->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($v->errors());
        }

        //Set session shippingMethod
        if (!bc_config('shipping_off')) {
            session(['shippingMethod' => request('shippingMethod')]);
        }

        //Set session paymentMethod
        if (!bc_config('payment_off')) {
            session(['paymentMethod' => request('paymentMethod')]);
        }

        //Set session address process
        session(['address_process' => request('address_process')]);
        //Set session shippingAddressshippingAddress
        session(
            [
                'shippingAddress' => [
                    'first_name'      => request('first_name'),
                    'last_name'       => request('last_name'),
                    'first_name_kana' => request('first_name_kana'),
                    'last_name_kana'  => request('last_name_kana'),
                    'email'           => request('email'),
                    'country'         => request('country'),
                    'address1'        => request('address1'),
                    'address2'        => request('address2'),
                    'address3'        => request('address3'),
                    'phone'           => request('phone'),
                    'postcode'        => request('postcode'),
                    'company'         => request('company'),
                    'comment'         => request('comment'),
                ],
            ]
        );

        //Check minimum
        $arrCheckQty = [];
        $cart = Cart::instance('default')->content()->toArray();
        foreach ($cart as $key => $row) {
            $arrCheckQty[$row['id']] = ($arrCheckQty[$row['id']] ?? 0) + $row['qty'];
        }
        $arrProductMinimum = ShopProduct::whereIn('id', array_keys($arrCheckQty))->pluck('minimum', 'id')->all();
        $arrErrorQty = [];
        foreach ($arrProductMinimum as $pId => $min) {
            if ($arrCheckQty[$pId] < $min) {
                $arrErrorQty[$pId] = $min;
            }
        }
        if (count($arrErrorQty)) {
            return redirect(bc_route('cart'))->with('arrErrorQty', $arrErrorQty);
        }
        //End check minimum

        return redirect(bc_route('checkout'));
    }

    /**
     * Process front checkout screen
     *
     * @param [type] ...$params
     * @return void
     */
    public function getCheckoutProcessFront(...$params) 
    {
        if (config('app.seoLang')) {
            $lang = $params[0] ?? '';
            bc_lang_switch($lang);
        }
        return $this->_getCheckout();
    }

    /**
     * Checkout screen
     * @return [view]
     */
    private function _getCheckout()
    {
        //Check shipping address
        if (
            !session('shippingAddress')
        ) {
            return redirect(bc_route('cart'));
        }
        $shippingAddress = session('shippingAddress');


        //Shipping method
        if (bc_config('shipping_off')) {
            $shippingMethodData = null;
        } else {
            if (!session('shippingMethod')) {
                return redirect(bc_route('cart'));
            }
            $shippingMethod = session('shippingMethod');
            $classShippingMethod = bc_get_class_plugin_config('Shipping', $shippingMethod);
            $shippingMethodData = (new $classShippingMethod)->getData();
        }

        //Payment method
        if (bc_config('payment_off')) {
            $paymentMethodData = null;
        } else {
            if (!session('paymentMethod')) {
                return redirect(bc_route('cart'));
            }
            $paymentMethod = session('paymentMethod');
            $classPaymentMethod = bc_get_class_plugin_config('Payment', $paymentMethod);
            $paymentMethodData = (new $classPaymentMethod)->getData();
        }
        $objects = ShopOrderTotal::getObjectOrderTotal();
        $dataTotal = ShopOrderTotal::processDataTotal($objects);

        //Set session dataTotal
        session(['dataTotal' => $dataTotal]);

        bc_check_view($this->templatePath . '.Cart.checkout');
        return view(
            $this->templatePath . '.Cart.checkout',
            [
                'title'              => trans('front.checkout_title'),
                'cart'               => Cart::instance('default')->content(),
                'dataTotal'          => $dataTotal,
                'paymentMethodData'  => $paymentMethodData,
                'shippingMethodData' => $shippingMethodData,
                'shippingAddress'    => $shippingAddress,
                'attributesGroup'    => ShopAttributeGroup::getListAll(),
                'layout_page'        => 'checkout',
            ]
        );
    }

    /**
     * Add to cart by method post, always use in the product page detail
     * 
     * @return [redirect]
     */
    public function addToCart()
    {
        $data      = request()->all();

        //Process escape
        $data      = bc_clean($data);

        $productId = $data['product_id'];
        $qty       = $data['qty'] ?? 0;
        $storeId   = $data['storeId'] ?? config('app.storeId');

        //Process attribute price
        $formAttr = $data['form_attr'] ?? null;
        $optionPrice  = 0;
        if ($formAttr) {
            foreach ($formAttr as $key => $attr) {
                $optionPrice += explode('__', $attr)[1] ??0;
            }
        }
        //End addtribute price
        $product = (new ShopProduct)->getDetail($productId, null, $storeId);

        if (!$product) {
            return response()->json(
                [
                    'error' => 1,
                    'msg' => trans('front.notfound'),
                ]
            );
        }
        
        if ($product->allowSale()) {
            $options = array();
            $options = $formAttr;
            $dataCart = array(
                'id'      => $product->id,
                'name'    => $product->name,
                'qty'     => $qty,
                'price'   => $product->getFinalPrice() + $optionPrice,
                'tax'     => $product->getTaxValue(),
                'storeId' => $storeId,
            );
            if ($options) {
                $dataCart['options'] = $options;
            }
            Cart::instance('default')->add($dataCart);
        } else {
            return redirect(bc_route('cart'))
                ->with(
                    ['error' => trans('cart.dont_allow_sale')]
                );
        }
        return redirect(bc_route('cart'))->with(['success' => trans('cart.success', ['instance' => 'cart'])]);

    }

    /**
     * Create new order
     * @return [redirect]
     */
    public function addOrder(Request $request)
    {
        $customer = auth()->user();
        $uID = $customer->id ?? 0;
        //if cart empty
        if (Cart::instance('default')->count() == 0) {
            return redirect()->route('home');
        }
        //Not allow for guest
        if (!bc_config('shop_allow_guest') && !$customer) {
            return redirect(bc_route('login'));
        } //

        $data = request()->all();
        if (!$data) {
            return redirect(bc_route('cart'));
        } else {
            $dataTotal       = session('dataTotal') ?? [];
            $shippingAddress = session('shippingAddress') ?? [];
            $paymentMethod   = session('paymentMethod') ?? '';
            $shippingMethod  = session('shippingMethod') ?? '';
            $address_process = session('address_process') ?? '';
        }

        //Process total
        $subtotal = (new ShopOrderTotal)->sumValueTotal('subtotal', $dataTotal); //sum total
        $tax      = (new ShopOrderTotal)->sumValueTotal('tax', $dataTotal); //sum tax
        $shipping = (new ShopOrderTotal)->sumValueTotal('shipping', $dataTotal); //sum shipping
        $discount = (new ShopOrderTotal)->sumValueTotal('discount', $dataTotal); //sum discount
        $received = (new ShopOrderTotal)->sumValueTotal('received', $dataTotal); //sum received
        $total    = (new ShopOrderTotal)->sumValueTotal('total', $dataTotal);
        //end total

        $dataOrder['customer_id']     = $uID;
        $dataOrder['subtotal']        = $subtotal;
        $dataOrder['shipping']        = $shipping;
        $dataOrder['discount']        = $discount;
        $dataOrder['received']        = $received;
        $dataOrder['tax']             = $tax;
        $dataOrder['payment_status']  = self::PAYMENT_UNPAID;
        $dataOrder['shipping_status'] = self::SHIPPING_NOTSEND;
        $dataOrder['status']          = self::ORDER_STATUS_NEW;
        $dataOrder['currency']        = bc_currency_code();
        $dataOrder['exchange_rate']   = bc_currency_rate();
        $dataOrder['total']           = $total;
        $dataOrder['balance']         = $total + $received;
        $dataOrder['email']           = $shippingAddress['email'];
        $dataOrder['first_name']      = $shippingAddress['first_name'];
        $dataOrder['payment_method']  = $paymentMethod;
        $dataOrder['shipping_method'] = $shippingMethod;
        $dataOrder['user_agent']      = $request->header('User-Agent');
        $dataOrder['ip']              = $request->ip();
        $dataOrder['created_at']      = date('Y-m-d H:i:s');

        if (!empty($shippingAddress['last_name'])) {
            $dataOrder['last_name']       = $shippingAddress['last_name'];
        }
        if (!empty($shippingAddress['first_name_kana'])) {
            $dataOrder['first_name_kana']       = $shippingAddress['first_name_kana'];
        }
        if (!empty($shippingAddress['last_name_kana'])) {
            $dataOrder['last_name_kana']       = $shippingAddress['last_name_kana'];
        }
        if (!empty($shippingAddress['address1'])) {
            $dataOrder['address1']       = $shippingAddress['address1'];
        }
        if (!empty($shippingAddress['address2'])) {
            $dataOrder['address2']       = $shippingAddress['address2'];
        }
        if (!empty($shippingAddress['address3'])) {
            $dataOrder['address3']       = $shippingAddress['address3'];
        }
        if (!empty($shippingAddress['country'])) {
            $dataOrder['country']       = $shippingAddress['country'];
        }
        if (!empty($shippingAddress['phone'])) {
            $dataOrder['phone']       = $shippingAddress['phone'];
        }
        if (!empty($shippingAddress['postcode'])) {
            $dataOrder['postcode']       = $shippingAddress['postcode'];
        }
        if (!empty($shippingAddress['company'])) {
            $dataOrder['company']       = $shippingAddress['company'];
        }
        if (!empty($shippingAddress['comment'])) {
            $dataOrder['comment']       = $shippingAddress['comment'];
        }

        $arrCartDetail = [];
        foreach (Cart::instance('default')->content() as $cartItem) {
            $arrDetail['product_id']  = $cartItem->id;
            $arrDetail['name']        = $cartItem->name;
            $arrDetail['price']       = bc_currency_value($cartItem->price);
            $arrDetail['qty']         = $cartItem->qty;
            $arrDetail['store_id']    = $cartItem->storeId;
            $arrDetail['attribute']   = ($cartItem->options) ? json_encode($cartItem->options) : null;
            $arrDetail['total_price'] = bc_currency_value($cartItem->price) * $cartItem->qty;
            $arrCartDetail[]          = $arrDetail;
        }

        //Set session info order
        session(['dataOrder' => $dataOrder]);
        session(['arrCartDetail' => $arrCartDetail]);
        //Create new order
        $newOrder = (new ShopOrder)->createOrder($dataOrder, $dataTotal, $arrCartDetail);

        if ($newOrder['error'] == 1) {
            return redirect(bc_route('cart'))->with(['error' => $newOrder['msg']]);
        }
        //Set session orderID
        session(['orderID' => $newOrder['orderID']]);

        //Create new address
        if ($address_process == 'new') {
            $addressNew = [
                'first_name'      => $shippingAddress['first_name'] ?? '',
                'last_name'       => $shippingAddress['last_name'] ?? '',
                'first_name_kana' => $shippingAddress['first_name_kana'] ?? '',
                'last_name_kana'  => $shippingAddress['last_name_kana'] ?? '',
                'postcode'        => $shippingAddress['postcode'] ?? '',
                'address1'        => $shippingAddress['address1'] ?? '',
                'address2'        => $shippingAddress['address2'] ?? '',
                'address3'        => $shippingAddress['address3'] ?? '',
                'country'         => $shippingAddress['country'] ?? '',
                'phone'           => $shippingAddress['phone'] ?? '',
            ];

            //Process escape
            $addressNew = bc_clean($addressNew);

            ShopCustomer::find($uID)->addresses()->save(new ShopCustomerAddress($addressNew));
            session()->forget('address_process'); //destroy address_process
        }

        $paymentMethod = bc_get_class_plugin_controller('Payment', session('paymentMethod'));

        if ($paymentMethod) {
            // Check payment method
            return (new $paymentMethod)->processOrder();
        } else {
            return (new ShopCartController)->completeOrder();
        }
    }

    /**
     * Add product to cart
     * @param Request $request [description]
     * @return [json]
     */
    public function addToCartAjax(Request $request)
    {
        if (!$request->ajax()) {
            return redirect(bc_route('cart'));
        }
        $data     = request()->all();
        $instance = $data['instance'] ?? 'default';
        $id       = $data['id'] ?? '';
        $storeId  = $data['storeId'] ?? config('app.storeId');
        $cart     = Cart::instance($instance);
        $product    = (new ShopProduct)->getDetail($id, null, $storeId);
        $cart_items = '';
        $msg        = trans('cart.success', ['instance' => ($instance == 'default') ? 'cart' : $instance]);

        if (!$product) {
            return response()->json(
                [
                    'error' => 1,
                    'msg' => trans('front.notfound'),
                ]
            );
        }
        switch ($instance) {
            case 'default':
                if ($product->attributes->count() || $product->kind == BC_PRODUCT_GROUP) {
                    //Products have attributes or kind is group,
                    //need to select properties before adding to the cart
                    return response()->json(
                        [
                            'error' => 1,
                            'redirect' => $product->getUrl(),
                            'msg' => '',
                        ]
                    );
                }

                //Check product allow for sale
                if ($product->allowSale()) {
                    $cart->add(
                        array(
                            'id'      => $id,
                            'name'    => $product->name,
                            'qty'     => 1,
                            'price'   => $product->getFinalPrice(),
                            'tax'     => $product->getTaxValue(),
                            'storeId' => $storeId,
                        )
                    );
                    $carts_content = $cart->content();
                    $cart_items = view($this->templatePath . '.Common.cart_items',['carts'=>$carts_content])->render();
                } else {
                    return response()->json(
                        [
                            'error' => 1,
                            'msg' => trans('cart.dont_allow_sale'),
                        ]
                    );
                }
                break;

            default:
                //Favorite or Compare...
                ${'arrID' . $instance} = array_keys($cart->content()->groupBy('id')->toArray());
                if (!in_array($id, ${'arrID' . $instance})) {
                    try {
                        $cart->add(
                            array(
                                'id'      => $id,
                                'name'    => $product->name,
                                'qty'     => 1,
                                'price'   => $product->getFinalPrice(),
                                'tax'     => $product->getTaxValue(),
                                'storeId' => $storeId,
                            )
                        );
                    } catch (\Throwable $e) {
                        return response()->json(
                            [
                                'error' => 1,
                                'msg' => $e->getMessage(),
                            ]
                        );
                    }

                }elseif (in_array($id, ${'arrID' . $instance}) && $instance == 'favorite') {
                    try {
                        $item = Cart::instance($instance)->content()->groupBy('id')->toArray();
                        if (array_key_exists($id, $item)) {
                            $cart->remove($item[$id][0]['rowId']);
                            $msg   = trans('cart.delete_success', ['instance' => 'favorite']);
                        }
                    } catch (\Throwable $e) {
                        return response()->json(
                            [
                                'error' => 1,
                                'msg' => $e->getMessage(),
                            ]
                        );
                    }
                } 
                else {
                    return response()->json(
                        [
                            'error' => 1,
                            'msg' => trans('cart.exist', ['instance' => $instance]),
                        ]
                    );
                }
                break;
        }
        $carts      = Cart::getListCart($instance);
        return response()->json(
            [
                'error'      => 0,
                'count_cart' => $carts['count'],
                'instance'   => $instance,
                'subtotal'   => $carts['subtotal'],
                'msg'        => $msg,
                'html_items'  => $cart_items
            ]
        );
    }

    /**
     * Update product to cart
     * @param  Request $request [description]
     * @return [json]
     */
    public function updateToCart(Request $request)
    {
        if (!$request->ajax()) {
            return redirect(bc_route('cart'));
        }
        $data    = request()->all();
        $id      = $data['id'] ?? '';
        $rowId   = $data['rowId'] ?? '';
        $new_qty = $data['new_qty'] ?? 0;
        $storeId = $data['storeId'] ?? config('app.storeId');
        $product = (new ShopProduct)->getDetail($id, null, $storeId);
        
        if (!$product) {
            return response()->json(
                [
                    'error' => 1,
                    'msg' => trans('front.notfound'),
                ]
            );
        }
        
        if ($product->stock < $new_qty && !bc_config('product_buy_out_of_stock', $product->store_id)) {
            return response()->json(
                [
                    'error' => 1,
                    'msg' => trans('cart.over', ['item' => $product->sku]),
                ]
            );
        } else {
            Cart::instance('default')->update($rowId, ($new_qty) ? $new_qty : 0);
            return response()->json(
                [
                    'error' => 0,
                ]
            );
        }

    }

    /**
     * Process front favorite
     *
     * @param [type] ...$params
     * @return void
     */
    public function favoriteListProcessFront(...$params) 
    {
        if (config('app.seoLang')) {
            $lang = $params[0] ?? '';
            bc_lang_switch($lang);
        }
        return $this->_favorite();
    }

    /**
     * Get product in favorite
     * @return [view]
     */
    private function _favorite()
    {

        $favorites = Cart::instance('favorite')->content();
        bc_check_view($this->templatePath . '.Shop.shop_favorite');
        return view(
            $this->templatePath . '.Shop.shop_favorite',
            array(
                'title'       => trans('front.favorite'),
                'description' => '',
                'keyword'     => '',
                'favorites'    => $favorites,
                'layout_page' => 'shop_favorite',
            )
        );
    }

    /**
     * Process front compare
     *
     * @param [type] ...$params
     * @return void
     */
    public function compareProcessFront(...$params) 
    {
        if (config('app.seoLang')) {
            $lang = $params[0] ?? '';
            bc_lang_switch($lang);
        }
        return $this->_compare();
    }

    /**
     * Get product in compare
     * @return [view]
     */
    private function _compare()
    {
        $compare = Cart::instance('compare')->content();

        bc_check_view($this->templatePath . '.Product.compare');
        return view(
            $this->templatePath . '.Product.compare',
            array(
                'title'       => trans('front.compare'),
                'description' => '',
                'keyword'     => '',
                'compare'     => $compare,
                'layout_page' => 'shop_compare',
            )
        );
    }


    /**
     * Process front compare
     *
     * @param [type] ...$params
     * @return void
     */
    public function clearCartProcessFront(...$params) 
    {
        if (config('app.seoLang')) {
            $lang = $params[0] ?? '';
            $instance = $params[1] ?? 'cart';
            bc_lang_switch($lang);
        } else {
            $instance = $params[0] ?? 'cart';
        }
        return $this->_clearCart($instance);
    }


    /**
     * Clear all cart
     * @return [redirect]
     */
    private function _clearCart($instance = 'cart')
    {
        Cart::instance($instance)->destroy();
        return redirect(bc_route($instance));
    }

    /**
     * Process front remove item cart
     *
     * @param [type] ...$params
     * @return void
     */
    public function removeItemProcessFront(...$params) 
    {
        if (config('app.seoLang')) {
            $lang = $params[0] ?? '';
            $instance = $params[1] ?? 'cart';
            $id = $params[2] ?? '';
            bc_lang_switch($lang);
        } else {
            $instance = $params[0] ?? 'cart';
            $id = $params[1] ?? '';
        }
        return $this->_removeItem($instance, $id);
    }


    /**
     * Remove item from cart
     * @return [redirect]
     */
    private function _removeItem($instance = 'cart', $id = null)
    {
        if ($id === null) {
            return redirect(bc_route($instance));
        }
        if (array_key_exists($id, Cart::instance($instance)->content()->toArray())) {
            Cart::instance($instance)->remove($id);
        }
        return redirect(bc_route($instance));
    }

    
    /**
     * Complete order
     *
     * @return [redirect]
     */
    public function completeOrder()
    {
        $orderID = session('orderID') ??0;
        if ($orderID == 0){
            return redirect()->route('home', ['error' => 'Error Order ID!']);
        }
        Cart::destroy(); // destroy cart

        $paymentMethod = session('paymentMethod');
        $shippingMethod = session('shippingMethod');
        $totalMethod = session('totalMethod', []);

        $classPaymentConfig = bc_get_class_plugin_config('Payment', $paymentMethod);
        if (method_exists($classPaymentConfig, 'endApp')) {
            (new $classPaymentConfig)->endApp();
        }

        $classShippingConfig = bc_get_class_plugin_config('Shipping', $shippingMethod);
        if (method_exists($classShippingConfig, 'endApp')) {
            (new $classShippingConfig)->endApp();
        }

        if ($totalMethod && is_array($totalMethod)) {
            foreach ($totalMethod as $keyMethod => $valueMethod) {
                $classTotalConfig = bc_get_class_plugin_config('Total', $keyMethod);
                if (method_exists($classTotalConfig, 'endApp')) {
                    (new $classTotalConfig)->endApp(['orderID' => $orderID, 'code' => $valueMethod]);
                }
            }
        }

        session()->forget('paymentMethod'); //destroy paymentMethod
        session()->forget('shippingMethod'); //destroy shippingMethod
        session()->forget('totalMethod'); //destroy totalMethod
        session()->forget('otherMethod'); //destroy otherMethod
        session()->forget('dataTotal'); //destroy dataTotal
        session()->forget('dataOrder'); //destroy dataOrder
        session()->forget('arrCartDetail'); //destroy arrCartDetail
        session()->forget('orderID'); //destroy orderID

        if (bc_config('order_success_to_admin') || bc_config('order_success_to_customer')) {
            $data = ShopOrder::with('details')->find($orderID)->toArray();
            $checkContent = (new ShopEmailTemplate)->where('group', 'order_success_to_admin')->where('status', 1)->first();
            $checkContentCustomer = (new ShopEmailTemplate)->where('group', 'order_success_to_customer')->where('status', 1)->first();
            if ($checkContent || $checkContentCustomer) {

                $orderDetail = '';
                $orderDetail .= '<tr>
                                    <td>' . trans('email.order.sort') . '</td>
                                    <td>' . trans('email.order.sku') . '</td>
                                    <td>' . trans('email.order.name') . '</td>
                                    <td>' . trans('email.order.price') . '</td>
                                    <td>' . trans('email.order.qty') . '</td>
                                    <td>' . trans('email.order.total') . '</td>
                                </tr>';
                foreach ($data['details'] as $key => $detail) {
                    $product = (new ShopProduct)->getDetail($detail['product_id']);
                    $pathDownload = $product->downloadPath->path ?? '';
                    $nameProduct = $detail['name'];
                    if ($product && $pathDownload && $product->property == BC_PROPERTY_DOWNLOAD) {
                        $nameProduct .="<br><a href='".bc_path_download_render($pathDownload)."'>Download</a>";
                    }

                    $orderDetail .= '<tr>
                                    <td>' . ($key + 1) . '</td>
                                    <td>' . $detail['sku'] . '</td>
                                    <td>' . $nameProduct . '</td>
                                    <td>' . bc_currency_render($detail['price'], '', '', '', false) . '</td>
                                    <td>' . number_format($detail['qty']) . '</td>
                                    <td align="right">' . bc_currency_render($detail['total_price'], '', '', '', false) . '</td>
                                </tr>';
                }
                $dataFind = [
                    '/\{\{\$title\}\}/',
                    '/\{\{\$orderID\}\}/',
                    '/\{\{\$firstName\}\}/',
                    '/\{\{\$lastName\}\}/',
                    '/\{\{\$toname\}\}/',
                    '/\{\{\$address\}\}/',
                    '/\{\{\$address1\}\}/',
                    '/\{\{\$address2\}\}/',
                    '/\{\{\$address3\}\}/',
                    '/\{\{\$email\}\}/',
                    '/\{\{\$phone\}\}/',
                    '/\{\{\$comment\}\}/',
                    '/\{\{\$orderDetail\}\}/',
                    '/\{\{\$subtotal\}\}/',
                    '/\{\{\$shipping\}\}/',
                    '/\{\{\$discount\}\}/',
                    '/\{\{\$total\}\}/',
                ];
                $dataReplace = [
                    trans('order.send_mail.new_title') . '#' . $orderID,
                    $orderID,
                    $data['first_name'],
                    $data['last_name'],
                    $data['first_name'].' '.$data['last_name'],
                    $data['address1'] . ' ' . $data['address2'].' '.$data['address3'],
                    $data['address1'],
                    $data['address2'],
                    $data['address3'],
                    $data['email'],
                    $data['phone'],
                    $data['comment'],
                    $orderDetail,
                    bc_currency_render($data['subtotal'], '', '', '', false),
                    bc_currency_render($data['shipping'], '', '', '', false),
                    bc_currency_render($data['discount'], '', '', '', false),
                    bc_currency_render($data['total'], '', '', '', false),
                ];

                // Send mail order success to admin 
                if (bc_config('order_success_to_admin') && $checkContent) {
                    $content = $checkContent->text;
                    $content = preg_replace($dataFind, $dataReplace, $content);
                    $dataView = [
                        'content' => $content,
                    ];
                    $config = [
                        'to' => bc_store('email'),
                        'subject' => trans('order.send_mail.new_title') . '#' . $orderID,
                    ];
                    bc_send_mail($this->templatePath . '.Mail.order_success_to_admin', $dataView, $config, []);
                }

                // Send mail order success to customer
                if (bc_config('order_success_to_customer') && $checkContentCustomer) {
                    $contentCustomer = $checkContentCustomer->text;
                    $contentCustomer = preg_replace($dataFind, $dataReplace, $contentCustomer);
                    $dataView = [
                        'content' => $contentCustomer,
                    ];
                    $config = [
                        'to' => $data['email'],
                        'replyTo' => bc_store('email'),
                        'subject' => trans('order.send_mail.new_title'),
                    ];

                    $attach = [];
                    if (bc_config('order_success_to_customer_pdf')) {
                        // Invoice pdf
                        \PDF::loadView($this->templatePath . '.Mail.order_success_to_customer_pdf', $dataView)
                            ->save(\Storage::disk('invoice')->path('order-'.$orderID.'.pdf'));
                        $attach['attachFromStorage'] = [
                            [
                                'file_storage' => 'invoice',
                                'file_path' => 'order-'.$orderID.'.pdf',
                            ]
                        ];
                    }

                    bc_send_mail($this->templatePath . '.Mail.order_success_to_customer', $dataView, $config, $attach);
                }
            }

        }

        return redirect(bc_route('order.success'))->with('orderID', $orderID);
    }


    /**
     * Process front page order success
     *
     * @param [type] ...$params
     * @return void
     */
    public function orderSuccessProcessFront(...$params) 
    {
        if (config('app.seoLang')) {
            $lang = $params[0] ?? '';
            bc_lang_switch($lang);
        }
        return $this->_orderSuccess();
    }

    /**
     * order success
     *
     * @return  [redirect to home with message]
     */
    private function _orderSuccess() {

        if (!session('orderID')) {
            return redirect()->route('home');
        }
        return redirect(bc_route('home'))->with('order_success', trans('order.success.title',['order_id'=>session('orderID')]));
    }

}
