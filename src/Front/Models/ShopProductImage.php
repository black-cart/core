<?php
#black-cart/Core/Front/Models/ShopProductImage.php
namespace BlackCart\Core\Front\Models;

use Illuminate\Database\Eloquent\Model;

class ShopProductImage extends Model
{
    public $timestamps = false;
    public $table = BC_DB_PREFIX.'shop_product_image';
    protected $guarded = [];
    protected $connection = BC_CONNECTION;

/*
Get thumb
 */
    public function getThumb()
    {
        return bc_image_get_path_thumb($this->image);
    }

/*
Get image
 */
    public function getImage()
    {
        return bc_image_get_path($this->image);

    }
}
