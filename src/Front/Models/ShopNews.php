<?php
#black-cart/Core/Front/Models/ShopNews.php
namespace BlackCart\Core\Front\Models;

use BlackCart\Core\Front\Models\ShopNewsDescription;
use Illuminate\Database\Eloquent\Model;
use Cache;
use BlackCart\Core\Front\Models\ModelTrait;
class ShopNews extends Model
{
    use ModelTrait;

    public $table = BC_DB_PREFIX.'shop_news';
    protected $guarded = [];
    protected $connection = BC_CONNECTION;

    public function descriptions()
    {
        return $this->hasMany(ShopNewsDescription::class, 'news_id', 'id');
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
    /**
     * [getUrl description]
     * @return [type] [description]
     */
    public function getUrl()
    {
        return bc_route('news.detail', ['alias' => $this->alias]);
    }

    //Scort
    public function scopeSort($query, $sortBy = null, $sortOrder = 'asc')
    {
        $sortBy = $sortBy ?? 'sort';
        return $query->orderBy($sortBy, $sortOrder);
    }


    /**
     * Get news detail
     *
     * @param   [string]  $key     [$key description]
     * @param   [string]  $type  [id, alias]
     *
     */
    public function getDetail($key, $type = null)
    {
        if(empty($key)) {
            return null;
        }
        $tableDescription = (new ShopNewsDescription)->getTable();
        $news = $this
            ->leftJoin($tableDescription, $tableDescription . '.news_id', $this->getTable() . '.id')
            ->where($tableDescription . '.lang', bc_get_locale());

        if ($type === null) {
            $news = $news->where('id', (int) $key);
        } else {
            $news = $news->where($type, $key);
        }
        $news = $news->where('status', 1)
            ->where('store_id', config('app.storeId'))
            ->first();
        return $news;
    }

    protected static function boot()
    {
        parent::boot();
        // before delete() method call this
        static::deleting(function ($news) {
            $news->descriptions()->delete();
            }
        );
    }

    /**
     * Start new process get data
     *
     * @return  new model
     */
    public function start() {
        return new ShopNews;
    }

    /**
     * build Query
     */
    public function buildQuery() {
        $tableDescription = (new ShopNewsDescription)->getTable();

        //description
        $query = $this
            ->leftJoin($tableDescription, $tableDescription . '.news_id', $this->getTable() . '.id')
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
                if(count($where)) {
                    $query = $query->where($where[0], $where[1], $where[2]);
                }
            }
        }

        if ($this->bc_random) {
            $query = $query->inRandomOrder();
        } else {
            $ckeckSort = false;
            if (is_array($this->bc_sort) && count($this->bc_sort)) {
                foreach ($this->bc_sort as  $rowSort) {
                    if (is_array($rowSort) && count($rowSort) == 2) {
                        if ($rowSort[0] == 'sort') {
                            $ckeckSort = true;
                        }
                        $query = $query->sort($rowSort[0], $rowSort[1]);
                    }
                }
            }
        }
        //Use field "sort" if haven't above
        if (!$ckeckSort) {
            $query = $query->orderBy($this->getTable().'.sort', 'asc');
        }
        //Default, will sort id
        $query = $query->orderBy($this->getTable().'.id', 'desc');

        return $query;
    }

}
