<?php
#black-cart/Core/Front/Models/ShopProductGroup.php
namespace BlackCart\Core\Front\Models;

use Illuminate\Database\Eloquent\Model;
use BlackCart\Core\Front\Models\ShopProduct;

class ShopProductGroup extends Model
{
    protected $primaryKey = ['group_id', 'product_id'];
    public $incrementing  = false;
    protected $guarded    = [];
    public $timestamps    = false;
    public $table = BC_DB_PREFIX.'shop_product_group';
    protected $connection = BC_CONNECTION;

    public function product()
    {
        return $this->belongsTo(ShopProduct::class, 'product_id', 'id');
    }
}
