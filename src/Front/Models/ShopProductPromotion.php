<?php
#black-cart/Core/Front/Models/ShopProductPromotion.php
namespace BlackCart\Core\Front\Models;

use Illuminate\Database\Eloquent\Model;
use BlackCart\Core\Front\Models\ShopProduct;
class ShopProductPromotion extends Model
{
    public $table = BC_DB_PREFIX.'shop_product_promotion';
    protected $guarded    = [];
    protected $primaryKey = ['product_id'];
    public $incrementing  = false;
    protected $connection = BC_CONNECTION;

    public function product()
    {
        return $this->belongsTo(ShopProduct::class, 'product_id', 'id');
    }

}
