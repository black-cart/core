<?php
#black-cart/Core/Front/Models/ShopPaymentStatus.php
namespace BlackCart\Core\Front\Models;

use Illuminate\Database\Eloquent\Model;

class ShopPaymentStatus extends Model
{
    public $timestamps  = false;
    public $table = BC_DB_PREFIX.'shop_payment_status';
    protected $guarded   = [];
    protected $connection = BC_CONNECTION;
    protected static $listStatus = null;
    public static function getIdAll()
    {
        if (!self::$listStatus) {
            self::$listStatus = self::pluck('name', 'id')->all();
        }
        return self::$listStatus;
    }
}
