<?php
#black-cart/Core/Front/Models/ShopPage.php
namespace BlackCart\Core\Front\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;
use BlackCart\Core\Front\Models\ModelTrait;
class ShopPage extends Model
{
    use ModelTrait;

    public $timestamps     = false;
    public $table          = BC_DB_PREFIX.'shop_page';
    protected $connection  = BC_CONNECTION;
    protected $guarded     = [];

    public function descriptions()
    {
        return $this->hasMany(ShopPageDescription::class, 'page_id', 'id');
    }

    //Function get text description 
    public function getText() {
        return $this->descriptions()->where('lang', bc_get_locale())->first();
    }
    public function getTitle() {
        return $this->getText()->title;
    }
    public function getDescription() {
        return $this->getText()->description;
    }
    public function getKeyword() {
        return $this->getText()->keyword;
    }
    public function getContent() {
        return $this->getText()->content;
    }
    //End  get text description


    /*
    *Get thumb
    */
    public function getThumb()
    {
        return bc_image_get_path_thumb($this->image);
    }

    /*
    *Get image
    */
    public function getImage()
    {
        return bc_image_get_path($this->image);

    }

    public function getUrl()
    {
        return bc_route('page.detail', ['alias' => $this->alias]);
    }

    /**
     * Get page detail
     *
     * @param   [string]  $key     [$key description]
     * @param   [string]  $type  [id, alias]
     *
     */
    public function getDetail($key, $type = null)
    {
        if (empty($key)) {
            return null;
        }
        $tableDescription = (new ShopPageDescription)->getTable();
        $page = $this
            ->leftJoin($tableDescription, $tableDescription . '.page_id', $this->getTable() . '.id')
            ->where($tableDescription . '.lang', bc_get_locale());

        if ($type === null) {
            $page = $page->where('id', (int) $key);
        } else {
            $page = $page->where($type, $key);
        }
        $page = $page->where('status', 1)
        ->where('store_id', config('app.storeId'));

        return $page->first();
    }

    protected static function boot()
    {
        parent::boot();
        // before delete() method call this
        static::deleting(function ($page) {
            $page->descriptions()->delete();
        }
        );
    }


    /**
     * Start new process get data
     *
     * @return  new model
     */
    public function start() {
        return new ShopPage;
    }

    /**
     * build Query
     */
    public function buildQuery() {
        $tableDescription = (new ShopPageDescription)->getTable();

        //description
        $query = $this
            ->leftJoin($tableDescription, $tableDescription . '.page_id', $this->getTable() . '.id')
            ->where($tableDescription . '.lang', bc_get_locale());
        //search keyword
        if ($this->bc_keyword !='') {
            $query = $query->where(function ($sql) use($tableDescription){
                $sql->where($tableDescription . '.title', 'like', '%' . $this->bc_keyword . '%')
                ->orWhere($tableDescription . '.keyword', 'like', '%' . $this->bc_keyword . '%')
                ->orWhere($tableDescription . '.description', 'like', '%' . $this->bc_keyword . '%');
            });
        }

        $query = $query->where('status', 1)
            ->where('store_id', config('app.storeId'));

        if (count($this->bc_moreWhere)) {
            foreach ($this->bc_moreWhere as $key => $where) {
                if (count($where)) {
                    $query = $query->where($where[0], $where[1], $where[2]);
                }
            }
        }

        if ($this->random) {
            $query = $query->inRandomOrder();
        } else {
            if (is_array($this->bc_sort) && count($this->bc_sort)) {
                foreach ($this->bc_sort as  $rowSort) {
                    if (is_array($rowSort) && count($rowSort) == 2) {
                        $query = $query->sort($rowSort[0], $rowSort[1]);
                    }
                }
            }
        }

        return $query;
    }
}
