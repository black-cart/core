<?php
#black-cart/Core/Front/Models/ShopLayoutPage.php
namespace BlackCart\Core\Front\Models;

use Illuminate\Database\Eloquent\Model;

class ShopLayoutPage extends Model
{
    public $timestamps = false;
    public $table = BC_DB_PREFIX.'shop_layout_page';
    protected $connection = BC_CONNECTION;

    public static function getPages()
    {
        return self::pluck('name', 'key')->all();
    }
}
