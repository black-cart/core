<?php
namespace BlackCart\Core\Front\Models;

use Illuminate\Database\Eloquent\Model;

class ShopCategoryDescription extends Model
{
    protected $primaryKey = ['category_id', 'lang'];
    public $incrementing  = false;
    public $timestamps    = false;
    public $table = BC_DB_PREFIX.'shop_category_description';
    protected $connection = BC_CONNECTION;
    protected $guarded    = [];
}
