<?php
#black-cart/Core/Front/Models/ShopStoreDescription.php
namespace BlackCart\Core\Front\Models;

use Illuminate\Database\Eloquent\Model;

class ShopStoreDescription extends Model
{
    protected $primaryKey = ['lang', 'store_id'];
    public $incrementing = false;
    protected $guarded = [];
    public $timestamps = false;
    public $table = BC_DB_PREFIX.'admin_store_description';
    protected $connection = BC_CONNECTION;
}
