<?php
#black-cart/Core/Front/Models/ShopWeight.php
namespace BlackCart\Core\Front\Models;

use Illuminate\Database\Eloquent\Model;

class ShopWeight extends Model
{
    public $timestamps     = false;
    public $table = BC_DB_PREFIX.'shop_weight';
    protected $connection = BC_CONNECTION;
    protected $guarded           = [];
    protected static $getList = null;

    public static function getListAll()
    {
        if (!self::$getList) {
            self::$getList = self::pluck('description', 'name')->all();
        }
        return self::$getList;
    }
}
