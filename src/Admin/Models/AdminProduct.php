<?php

namespace BlackCart\Core\Admin\Models;

use BlackCart\Core\Front\Models\ShopProduct;
use BlackCart\Core\Front\Models\ShopProductDescription;
use BlackCart\Core\Front\Models\ShopAttributeGroup;
use BlackCart\Core\Front\Models\ShopProductCategory;

class AdminProduct extends ShopProduct
{
    /**
     * Get product table name in admin
     *
     * @param   [type]  $id  [$id description]
     *
     * @return  [type]       [return description]
     */
    public static function gettableProduct()
    {
        $tableProduct = (new ShopProduct())->getTable();
        return self::where($tableProduct . '.store_id', session('adminStoreId'));
    }
    /**
     * Get product detail in admin
     *
     * @param   [type]  $id  [$id description]
     *
     * @return  [type]       [return description]
     */
    public static function getProductAdmin($id) {
        $tableDescription = (new ShopProductDescription())->getTable();
        $tableProduct = (new ShopProduct())->getTable();
        return self::where('id', $id)
        ->leftJoin($tableDescription, $tableDescription . '.product_id', $tableProduct . '.id')
        ->where($tableProduct . '.store_id', session('adminStoreId'))
        ->first();
    }

    /**
     * Get list product in admin
     *
     * @param   [array]  $dataSearch  [$dataSearch description]
     *
     * @return  [type]               [return description]
     */
    public static function getProductListAdmin(array $dataSearch) {
        $keyword          = $dataSearch['keyword'] ?? '';
        $category_id      = $dataSearch['category_id'] ?? '';
        $sort_order       = $dataSearch['sort_order'] ?? '';
        $arrSort          = $dataSearch['arrSort'] ?? '';
        $price_max        = $dataSearch['price_max'] ?? '';
        $price_min        = $dataSearch['price_min'] ?? '';

        $tableDescription = (new ShopProductDescription)->getTable();
        $tablePTC         = (new ShopProductCategory)->getTable();
        $tableProduct     = (new ShopProduct)->getTable();
        if ($category_id) {
            $productList = (new ShopProduct)
                ->leftJoin($tableDescription, $tableDescription . '.product_id', $tableProduct . '.id')
                ->join($tablePTC, $tablePTC . '.product_id', $tableProduct . '.id')
                ->where($tablePTC . '.category_id', $category_id)
                ->where($tableProduct . '.store_id', session('adminStoreId'))
                ->where($tableDescription . '.lang', bc_get_locale());
        } else {
            $productList = (new ShopProduct)
                ->leftJoin($tableDescription, $tableDescription . '.product_id', $tableProduct . '.id')
                ->where($tableProduct . '.store_id', session('adminStoreId'))
                ->where($tableDescription . '.lang', bc_get_locale());
        }

        if ($keyword) {
            $productList = $productList->where(function ($sql) use($tableDescription, $tableProduct, $keyword){
                $sql->where($tableDescription . '.name', 'like', '%' . $keyword . '%')
                    ->orWhere($tableDescription . '.keyword', 'like', '%' . $keyword . '%')
                    ->orWhere($tableDescription . '.description', 'like', '%' . $keyword . '%')
                    ->orWhere($tableProduct . '.sku', 'like', '%' . $keyword . '%');
            });
        }
        if ($price_max || $price_min) {
            $productList = $productList->where([[$tableProduct.'.price','>=',$price_min],[$tableProduct.'.price','<=',$price_max]]);
        }
        $productList->groupBy($tableProduct.'.id');

        if ($sort_order && array_key_exists($sort_order, $arrSort)) {
            $field = explode('__', $sort_order)[0];
            $sort_field = explode('__', $sort_order)[1];
            $productList = $productList->sort($field, $sort_field);
        } else {
            $productList = $productList->sort('id', 'desc');
        }
        $productList = $productList->paginate(20);

        return $productList;
    }

    /**
     * Get list product select in admin
     *
     * @param   array  $dataFilter  [$dataFilter description]
     *
     * @return  []                  [return description]
     */
    public function getProductSelectAdmin(array $dataFilter = []) {
        $keyword          = $dataFilter['keyword'] ?? '';
        $limit            = $dataFilter['limit'] ?? '';
        $kind             = $dataFilter['kind'] ?? [];
        $tableDescription = (new ShopProductDescription)->getTable();
        $tableProduct     = $this->getTable();
        $colSelect = [
            'id',
            'image',
            'sku',
             $tableDescription . '.name'
        ];
        $productList = (new ShopProduct)->select($colSelect)
            ->leftJoin($tableDescription, $tableDescription . '.product_id', $tableProduct . '.id')
            ->where($tableProduct . '.store_id', session('adminStoreId'))
            ->where($tableDescription . '.lang', bc_get_locale());
        if(is_array($kind) && $kind) {
            $productList = $productList->whereIn('kind', $kind);
        }
        if ($keyword) {
            $productList = $productList->where(function ($sql) use($tableDescription, $tableProduct, $keyword){
                $sql->where($tableDescription . '.name', 'like', '%' . $keyword . '%')
                    ->orWhere($tableProduct . '.sku', 'like', '%' . $keyword . '%');
            });
        }

        if($limit) {
            $productList = $productList->limit($limit);
        }
        $productList->groupBy($tableProduct.'.id');
        return $productList->get()->keyBy('id');
    }


    /**
     * Create a new product
     *
     * @param   array  $dataInsert  [$dataInsert description]
     *
     * @return  [type]              [return description]
     */
    public static function createProductAdmin(array $dataInsert) {
        return self::create($dataInsert);
    }


    /**
     * Insert data description
     *
     * @param   array  $dataInsert  [$dataInsert description]
     *
     * @return  [type]              [return description]
     */
    public static function insertDescriptionAdmin(array $dataInsert) {
        return ShopProductDescription::create($dataInsert);
    }

    /**
     * [checkProductValidationAdmin description]
     *
     * @param   [type]$type     [$type description]
     * @param   null  $fieldValue    [$field description]
     * @param   null  $pId      [$pId description]
     * @param   null  $storeId  [$storeId description]
     * @param   null            [ description]
     *
     * @return  [type]          [return description]
     */
    public function checkProductValidationAdmin($type = null, $fieldValue = null, $pId = null, $storeId = null) {
        $storeId = $storeId ? bc_clean($storeId) : session('adminStoreId');
        $type = $type ? bc_clean($type) : 'sku';
        $fieldValue = bc_clean($fieldValue);
        $pId = bc_clean($pId);
        $check =  $this
        ->where($type, $fieldValue)
        ->where($this->getTable() . '.store_id', $storeId);
        if($pId) {
            $check = $check->where('id', '<>', $pId);
        }
        $check = $check->first();

        if($check) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get total product of system
     *
     * @return  [type]  [return description]
     */
    public static function getTotalProduct() {
        return self::count();
    }
    

    /**
     * Render html option price in admin
     *
     * @param   [type]$currency  [$currency description]
     * @param   nul   $rate      [$rate description]
     * @param   null             [ description]
     *
     * @return  [type]           [return description]
     */
    public function renderAttributeDetailsAdmin($currency = nul, $rate = null)
    {
        $html = '';
        $details = $this->attributes()->get()->groupBy('attribute_group_id');
        $groups = ShopAttributeGroup::getListAll();
        foreach ($details as $groupId => $detailsGroup) {
            $html .= '<br><b><label>' . $groups[$groupId] . '</label></b>: ';
            foreach ($detailsGroup as $k => $detail) {
                $valueOption = $detail->name.'__'.$detail->add_price;
                $html .= '<label class="radio-inline"><input ' . (($k == 0) ? "checked" : "") . ' type="radio" name="add_att[' . $this->id . '][' . $groupId . ']" value="' . $valueOption . '">' . bc_render_option_price($valueOption, $currency, $rate) . '</label> ';
            }
        }
        return $html;
    }    

}
