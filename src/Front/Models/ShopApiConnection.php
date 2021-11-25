<?php
namespace BlackCart\Core\Front\Models;

use Illuminate\Database\Eloquent\Model;

class ShopApiConnection extends Model
{
    public $timestamps = false;
    public $table = BC_DB_PREFIX.'api_connection';
    protected $guarded = [];
    protected $connection = BC_CONNECTION;
    protected static $getGroup = null;

    public static function check($apiconnection, $apikey)
    {
        return self::where('apikey', $apikey)
                    ->where('apiconnection', $apiconnection)
                    ->where(function ($query) {
                        $query->whereNull('expire')
                              ->orWhere('expire', '>=', date('Y-m-d'));
                    })
                    ->where('status', 1)
                    ->first();
    }
}
