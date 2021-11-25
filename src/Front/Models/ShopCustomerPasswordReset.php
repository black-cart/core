<?php
#black-cart/Core/Front/Models/ShopCustomerPasswordReset.php
namespace BlackCart\Core\Front\Models;

use Illuminate\Database\Eloquent\Model;

class ShopCustomerPasswordReset extends Model
{
    protected $primaryKey = ['token'];
    public $incrementing  = false;
    protected $guarded    = [];
    public $timestamps    = false;
    public $table = BC_DB_PREFIX.'shop_password_resets';
    protected $connection = BC_CONNECTION;
}
