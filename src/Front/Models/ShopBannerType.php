<?php
#black-cart/Core/Front/Models/ShopBannerType.php
namespace BlackCart\Core\Front\Models;

use Illuminate\Database\Eloquent\Model;

class ShopBannerType extends Model
{
    public $timestamps  = false;
    public $table = BC_DB_PREFIX.'shop_banner_type';
    protected $guarded   = [];
    protected $connection = BC_CONNECTION;
}
