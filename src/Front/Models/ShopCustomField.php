<?php
#black-cart/Core/Front/Models/ShopCustomField.php
namespace BlackCart\Core\Front\Models;

use Illuminate\Database\Eloquent\Model;
use BlackCart\Core\Front\Models\ShopCustomFieldDetail;
class ShopCustomField extends Model
{
    public $timestamps     = false;
    public $table          = BC_DB_PREFIX.'shop_custom_field';
    protected $connection  = BC_CONNECTION;
    protected $guarded     = [];

    public function details()
    {
        $data  = (new ShopCustomFieldDetail)->where('custom_field_id', $this->id)
            ->get();
        return $data;
    }

    /**
     * Get custom fields
     */
    public function getCustomField($type) {
        return $this->where('type', $type)
            ->where('status', 1)
            ->get();
    }

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
