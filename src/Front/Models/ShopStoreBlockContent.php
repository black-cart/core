<?php
#black-cart/Core/Front/Models/ShopStoreBlockContent.php
namespace BlackCart\Core\Front\Models;

use Illuminate\Database\Eloquent\Model;

class ShopStoreBlockContent extends Model
{
    public $timestamps = false;
    public $table = BC_DB_PREFIX.'shop_store_block';
    protected $guarded = [];
    private static $getLayout = null;
    protected $connection = BC_CONNECTION;

    public static function getLayout()
    {
        if (self::$getLayout === null) {
            self::$getLayout = self::where('status', 1)
                ->where('store_id', config('app.storeId'))
                ->orderBy('sort', 'asc')
                ->get()
                ->groupBy('position');
        }
        return self::$getLayout;
    }

}
