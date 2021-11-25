<?php
#black-cart/Core/Front/Models/ShopProductProperty.php
namespace BlackCart\Core\Front\Models;

use Illuminate\Database\Eloquent\Model;

class ShopProductProperty extends Model
{
    public $timestamps  = false;
    public $table = BC_DB_PREFIX.'shop_product_property';
    protected $guarded   = [];
    protected $connection = BC_CONNECTION;
}
