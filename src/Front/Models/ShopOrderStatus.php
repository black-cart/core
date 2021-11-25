<?php
#black-cart/Core/Front/Models/ShopOrderStatus.php
namespace BlackCart\Core\Front\Models;

use Illuminate\Database\Eloquent\Model;

class ShopOrderStatus extends Model
{
    public $timestamps     = false;
    public $table = BC_DB_PREFIX.'shop_order_status';
    protected $connection = BC_CONNECTION;
    protected $guarded           = [];
    protected static $listStatus = null;

    public static function getIdAll()
    {
        if (!self::$listStatus) {
            self::$listStatus = self::pluck('name', 'id')->all();
        }
        return self::$listStatus;
    }
}
