<?php
namespace BlackCart\Core\Front\Models;

use Illuminate\Database\Eloquent\Model;

class ShopAttributeGroup extends Model
{
    public $timestamps        = false;
    public $table = BC_DB_PREFIX.'shop_attribute_group';
    protected $guarded        = [];
    protected static $getListType = null;
    protected static $getListAll = null;
    protected $connection = BC_CONNECTION;

    public static function getListAll()
    {
        if (!self::$getListAll) {
            self::$getListAll = self::pluck('name', 'id')->all();
        }
        return self::$getListAll;
    }

    public static function getListType()
    {
        if (!self::$getListType) {
            self::$getListType = self::all()->keyBy('id');
        }
        return self::$getListType;
    }

    public function attributeDetails()
    {
        return $this->hasMany(ShopProductAttribute::class, 'attribute_group_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($group) {
            $group->attributeDetails()->delete();
        });
    }

}
