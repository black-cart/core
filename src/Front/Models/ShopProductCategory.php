<?php
#black-cart/Core/Front/Models/ShopProductCategory.php
namespace BlackCart\Core\Front\Models;

use Illuminate\Database\Eloquent\Model;

class ShopProductCategory extends Model
{
    protected $primaryKey = ['category_id', 'product_id'];
    public $incrementing  = false;
    protected $guarded    = [];
    public $timestamps    = false;
    public $table = BC_DB_PREFIX.'shop_product_category';
    protected $connection = BC_CONNECTION;
}
