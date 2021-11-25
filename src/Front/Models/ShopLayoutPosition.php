<?php
#black-cart/Core/Front/Models/ShopLayoutPosition.php
namespace BlackCart\Core\Front\Models;

use Illuminate\Database\Eloquent\Model;

class ShopLayoutPosition extends Model
{
    public $timestamps = false;
    public $table = BC_DB_PREFIX.'shop_layout_position';
    protected $connection = BC_CONNECTION;
    
    public static function getPositions()
    {
        return self::pluck('name', 'key')->all();
    }
}
