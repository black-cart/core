<?php
#black-cart/Core/Front/Models/ShopLink.php
namespace BlackCart\Core\Front\Models;

use Illuminate\Database\Eloquent\Model;

class ShopLink extends Model
{
    public $timestamps = false;
    public $table = BC_DB_PREFIX.'shop_link';
    protected $guarded = [];
    protected $connection = BC_CONNECTION;
    protected static $getGroup = null;

    public static function getGroup()
    {
        if (!self::$getGroup) {
            self::$getGroup = self::where('status', 1)
                ->where('store_id', config('app.storeId'))
                ->orderBy('sort', 'asc')
                ->orderBy('id', 'desc')
                ->get()->groupBy('group');
        }
        return self::$getGroup;
    }
}
