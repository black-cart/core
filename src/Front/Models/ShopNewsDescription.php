<?php
#black-cart/Core/Front/Models/ShopNewsDescription.php
namespace BlackCart\Core\Front\Models;

use Illuminate\Database\Eloquent\Model;

class ShopNewsDescription extends Model
{
    protected $primaryKey = ['lang', 'news_id'];
    public $incrementing = false;
    protected $guarded = [];
    public $timestamps = false;
    public $table = BC_DB_PREFIX.'shop_news_description';
    protected $connection = BC_CONNECTION;
}
