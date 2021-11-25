<?php
#black-cart/Core/Front/Models/ShopSubscribe.php
namespace BlackCart\Core\Front\Models;

use Illuminate\Database\Eloquent\Model;

class ShopSubscribe extends Model
{
    public $table = BC_DB_PREFIX.'shop_subscribe';
    protected $guarded      = [];
    protected $connection = BC_CONNECTION;
}
