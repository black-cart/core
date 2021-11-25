<?php
#black-cart/Core/Front/Models/ShopCustomFieldDetail.php
namespace BlackCart\Core\Front\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;
class ShopCustomFieldDetail extends Model
{
    public $timestamps     = false;
    public $table          = BC_DB_PREFIX.'shop_custom_field_detail';
    protected $connection  = BC_CONNECTION;
    protected $guarded     = [];

    //Function get text description 
    protected static function boot()
    {
        parent::boot();
        // before delete() method call this
        static::deleting(function ($obj) {
            //
        }
        );
    }

}
