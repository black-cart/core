<?php
#black-cart/Core/Front/Models/ShopOrderHistory.php
namespace BlackCart\Core\Front\Models;

use Illuminate\Database\Eloquent\Model;

class ShopOrderHistory extends Model
{
    public $table = BC_DB_PREFIX.'shop_order_history';
    protected $connection = BC_CONNECTION;
	const CREATED_AT = 'add_date';
	const UPDATED_AT = null;
    protected $guarded           = [];
}
