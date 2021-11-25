<?php
#black-cart/Core/Front/Models/ShopProductDescription.php
namespace BlackCart\Core\Front\Models;

use Illuminate\Database\Eloquent\Model;

class ShopProductDescription extends Model
{
    protected $primaryKey = ['lang', 'product_id'];
    public $incrementing  = false;
    protected $guarded    = [];
    public $timestamps    = false;
    public $table = BC_DB_PREFIX.'shop_product_description';
    protected $connection = BC_CONNECTION;
}
