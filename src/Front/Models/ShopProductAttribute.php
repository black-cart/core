<?php
#black-cart/Core/Front/Models/ShopProductAttribute.php
namespace BlackCart\Core\Front\Models;

use Illuminate\Database\Eloquent\Model;

class ShopProductAttribute extends Model
{
    public $timestamps = false;
    public $table = BC_DB_PREFIX.'shop_product_attribute';
    protected $guarded = [];
    protected $connection = BC_CONNECTION;
    public function attGroup()
    {
        return $this->belongsTo(ShopAttributeGroup::class, 'attribute_group_id', 'id');
    }
}
