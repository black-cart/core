<?php
#black-cart/Core/Front/Models/ShopCustomerAddress.php
namespace BlackCart\Core\Front\Models;

use Illuminate\Database\Eloquent\Model;

class ShopCustomerAddress extends Model
{
    protected $guarded    = [];
    public $timestamps    = false;
    public $table = BC_DB_PREFIX.'shop_customer_address';
    protected $connection = BC_CONNECTION;
}
