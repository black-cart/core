<?php
namespace BlackCart\Core\Front\Controllers;

use App\Http\Controllers\RootFrontController;
use BlackCart\Core\Front\Models\ShopProduct;
use BlackCart\Core\Front\Models\ShopProductDescription;
use BlackCart\Core\Front\Models\ShopAttributeGroup;
use Illuminate\Http\Request;
use Cache;

class ShopProductController extends RootFrontController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Process front all products
     *
     * @param [type] ...$params
     * @return void
     */
    public function allProductsProcessFront(...$params) 
    {
        if (config('app.seoLang')) {
            $lang = $params[0] ?? '';
            bc_lang_switch($lang);
        }
        return $this->_allProducts();
    }

    /**
     * All products
     * @return [view]
     */
    private function _allProducts()
    {
        $sortBy = 'sort';
        $sortOrder = 'asc';
        $filter_sort = request('filter_sort') ?? 'id_desc';
        $filter_price = request('filter_price') ?? '';
        $filter_keyword = request('filter_keyword') ?? '';
        $filter_attribute = request('filter_attribute') ?? '';
        $filter_category = request('categoryId') ?? '';
        
        $filterArrSort = [
            'price_desc' => ['price', 'desc'],
            'price_asc' => ['price', 'asc'],
            'sort_desc' => ['sort', 'desc'],
            'sort_asc' => ['sort', 'asc'],
            'id_desc' => ['id', 'desc'],
            'id_asc' => ['id', 'asc'],
        ];
        $products = (new ShopProduct);
        $price_max = $products->max('price');
        $filterArrPrice = [0,$price_max];

        if (array_key_exists($filter_sort, $filterArrSort)) {
            $sortBy = $filterArrSort[$filter_sort][0];
            $sortOrder = $filterArrSort[$filter_sort][1];
        }  
        if ($filter_price) {
            $filter_price = explode('-', request('filter_price'));
            $price_min = bc_convert_price_to_origin($filter_price[0]);
            $price_max = bc_convert_price_to_origin($filter_price[1]);
            $products = $products->setPriceBetween($price_min,$price_max);
        }  
        if ($filter_keyword) {
            $products = $products->setKeyword($filter_keyword);
        }
        if ($filter_keyword) {
            $products = $products->setKeyword($filter_keyword);
        }
        if ($filter_attribute) {
            $products = $products->setAttributes($filter_attribute);
        }        
        $products = $products
            ->setLimit(bc_config('product_list'))
            ->setPaginate()
            ->setSort([$sortBy, $sortOrder])
            ->getData();
    
        $filterArrKeyword = [];
        if (Cache::has(session('adminStoreId').'_cache_keyword_products_'.bc_get_locale())){
            $filterArrKeyword = Cache::get(session('adminStoreId').'_cache_keyword_products_'.bc_get_locale());
        }else{
            $ArrKeyword = ShopProductDescription::select('keyword')->where('lang', bc_get_locale())->get()->pluck('keyword')->toArray();
            $ArrKeyword = array_unique($ArrKeyword);
            foreach ($ArrKeyword as $value) {
                $keys = explode(',', $value);
                foreach ($keys as $key) {
                    if (!in_array($key,$filterArrKeyword)) {
                        array_push($filterArrKeyword, $key);
                    }
                }
            }
            bc_set_cache(session('adminStoreId').'_cache_keyword_products_'.bc_get_locale(), $filterArrKeyword);
        }

        $filterArrAttribute = [];
        if (Cache::has(session('adminStoreId').'_cache_attributes_products_'.bc_get_locale())){
            $filterArrAttribute = Cache::get(session('adminStoreId').'_cache_attributes_products_'.bc_get_locale());
        }else{
            $allAttributes = ShopAttributeGroup::with('attributeDetails')->where('picker',1)->get()->pluck('attributeDetails')[0]->pluck('code')->toArray();
            foreach (array_filter($allAttributes) as $key => $att) {
                foreach (explode(',', $att) as $key => $vatt) {
                    $filterArrAttribute[] = $vatt;
                }
            }
            $filterArrAttribute = array_unique($filterArrAttribute);
        }
        
        if(request()->ajax()){
            bc_check_view($this->templatePath . '.Common.isotope_grid');
            return view($this->templatePath . '.Common.isotope_grid',['products'=> $products])->render();   
        }

        bc_check_view($this->templatePath . '.Shop.index');
        return view(
            $this->templatePath . '.Shop.index',
            array(
                'title'            => trans('front.all_product'),
                'keyword'          => '',
                'description'      => '',
                'products'         => $products,
                'layout_page'      => 'shop_product_list',
                'filterArrSort'    => $filterArrSort,
                'filter_sort'      => $filter_sort,
                'filterArrPrice'   => $filterArrPrice,
                'filter_price'     => $filter_price ? implode('-', $filter_price) : '',
                'filterArrKeyword' => $filterArrKeyword,
                'filter_keyword'   => $filter_keyword,
                'filterArrAttribute' => $filterArrAttribute,
                'filter_attribute'   => $filter_attribute,
            )
        );
    }

    /**
     * Process front product detail
     *
     * @param [type] ...$params
     * @return void
     */
    public function productDetailProcessFront(...$params) 
    {
        if (config('app.seoLang')) {
            $lang = $params[0] ?? '';
            $alias = $params[1] ?? '';
            $storeId = $params[2] ?? '';
            bc_lang_switch($lang);
        } else {
            $alias = $params[0] ?? '';
            $storeId = $params[1] ?? '';
        }
        return $this->_productDetail($alias,'alias', $storeId);
    }

    /**
     * Get product detail
     *
     * @param   [string]  $alias      [$alias description]
     * @param   [string]  $type     [$type id or alias or sku]
     * @param   [string]  $storeId  [$storeCode description]
     * @param   [string]  $view  [$view check detail view or quick view]
     *
     * @return  [mix]
     */
    private function _productDetail($alias,$type, $storeId,$view = '.Product.detail')
    {
        $product = (new ShopProduct)->getDetail($alias, $type, $storeId);
        if ($product && $product->status && (!bc_config('product_stock', $storeId) || bc_config('product_display_out_of_stock', $storeId) || $product->stock > 0)) {
            //Update last view
            $product->view += 1;
            $product->date_lastview = date('Y-m-d H:i:s');
            $product->save();
            //End last viewed

            //Product last view
                $arrlastView = empty(\Cookie::get('productsLastView')) ? array() : json_decode(\Cookie::get('productsLastView'), true);
                $arrlastView[$product->id] = date('Y-m-d H:i:s');
                arsort($arrlastView);
                \Cookie::queue('productsLastView', json_encode($arrlastView), (86400 * 30));
            //End product last view

            $categories = $product->categories->keyBy('id')->toArray();
            $arrCategoriId = array_keys($categories);

            $productRelation = (new ShopProduct)
                ->getProductToCategory($arrCategoriId)
                ->setLimit(bc_config('product_relation', $storeId))
                ->setRandom()
                ->getData();

            bc_check_view($this->templatePath . $view);
            return view($this->templatePath . $view,
                array(
                    'title' => $product->name,
                    'description' => $product->description,
                    'keyword' => $product->keyword,
                    'product' => $product,
                    'productRelation' => $productRelation,
                    'goToStore' => $product->goToStore(),
                    'og_image' => asset($product->getImage()),
                    'layout_page' => 'product_detail',
                )
            );
        } else {
            return $this->itemNotFound();
        }
    }
    public function productDetailQuickViewProcess(Request $request)
    {
        return $this->_productDetail($request->id,'', $request->storeId,'.Common.product_detail')->render();
    }
}
