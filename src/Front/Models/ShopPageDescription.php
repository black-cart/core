<?php
#black-cart/Core/Front/Models/ShopPageDescription.php
namespace BlackCart\Core\Front\Models;

use Illuminate\Database\Eloquent\Model;

class ShopPageDescription extends Model
{
    protected $primaryKey = ['lang', 'page_id'];
    public $incrementing  = false;
    protected $guarded    = [];
    public $timestamps    = false;
    public $table = BC_DB_PREFIX.'shop_page_description';
    protected $connection = BC_CONNECTION;
}
