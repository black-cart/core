<?php
#app/Models/AdminUserStore.php
namespace BlackCart\Core\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class AdminUserStore extends Model
{
    protected $primaryKey = ['store_id', 'user_id'];
    public $incrementing  = false;
    protected $guarded    = [];
    public $timestamps    = false;
    public $table = BC_DB_PREFIX.'admin_user_store';
    protected $connection = BC_CONNECTION;
}
