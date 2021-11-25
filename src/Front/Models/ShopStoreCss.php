<?php
#black-cart/Core/Front/Models/ShopStoreCss.php
namespace BlackCart\Core\Front\Models;

use Illuminate\Database\Eloquent\Model;

class ShopStoreCss extends Model
{
    protected $primaryKey = 'store_id';
    public $incrementing  = false;
    protected $guarded    = [];
    public $timestamps    = false;
    public $table = BC_DB_PREFIX.'shop_store_css';
    protected $connection = BC_CONNECTION;
}
