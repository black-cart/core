<?php
namespace BlackCart\Core\Front\Models;

use BlackCart\Core\Front\Models\ShopCategoryDescription;
use BlackCart\Core\Front\Models\ShopProduct;
use BlackCart\Core\Admin\Models\AdminCategory;
use Illuminate\Database\Eloquent\Model;
use BlackCart\Core\Front\Models\ModelTrait;

class ShopCategory extends Model
{
    use ModelTrait;

    public $timestamps = false;
    public $table = BC_DB_PREFIX . 'shop_category';

    public $categoryTmp = [];
    public $id_disabled;

    protected $guarded = [];
    protected $connection = BC_CONNECTION;

    protected  $bc_parent = ''; // category id parent
    protected  $bc_top = 'all'; // 1 - category display top, 0 -non top, all - all

    public function products()
    {
        return $this->belongsToMany(ShopProduct::class, BC_DB_PREFIX . 'shop_product_category', 'category_id', 'product_id');
    }

    public function descriptions()
    {
        return $this->hasMany(ShopCategoryDescription::class, 'category_id', 'id');
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
    //End  get text description

    /**
     * Get category parent
     */
    public function getParent()
    {
        return $this->getDetail($this->parent);
    }

    protected static function boot()
    {
        parent::boot();
        // before delete() method call this
        static::deleting(function ($category) {
            //Delete category descrition
            $category->descriptions()->delete();
            $category->products()->detach();
        });
    }

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
        return bc_route('category.detail', ['alias' => $this->alias]);
    }

    //Scort
    public function scopeSort($query, $sortBy = null, $sortOrder = 'asc')
    {
        $sortBy = $sortBy ?? 'sort';
        return $query->orderBy($sortBy, $sortOrder);
    }

    /**
     * Get categoy detail
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
        $tableDescription = (new ShopCategoryDescription)->getTable();
        $category = $this
            ->leftJoin($tableDescription, $tableDescription . '.category_id', $this->getTable() . '.id')
            ->where($tableDescription . '.lang', bc_get_locale());

        if ($type === null) {
            $category = $category->where('id', (int) $key);
        } else {
            $category = $category->where($type, $key);
        }
        $category = $category->where('status', 1);
        return $category->first();
    }
    
    /**
     * Start new process get data
     *
     * @return  new model
     */
    public function start() {
        return new ShopCategory;
    }

    /**
     * Set category parent
     */
    public function setParent($parent) {
        $this->bc_parent = (int)$parent;
        return $this;
    }

    /**
     * Set top value
     */
    private function setTop($top) {
        if ($top === 'all') {
            $this->bc_top = $top;
        } else {
            $this->bc_top = (int)$top ? 1 : 0;
        }
        return $this;
    }

    /**
     * Category root
     */
    public function getCategoryRoot() {
        $this->setParent(0);
        return $this;
    }

    /**
     * Category top
     */
    public function getCategoryTop() {
        $this->setTop(1);
        return $this;
    }
    /**
     * Get Recursive category.
     *
     * @param  null
     * @return \Illuminate\Http\Response
     */
    public function categoryRecursive(){
        $data = $this->ExcuteRecursive();
        return $data;
    }
    public function ExcuteRecursive($id_parent = 0)
    {
        $data = [];
        foreach (self::setParent($id_parent)->getData() as $group)
        {
            $group['children'] = $this->ExcuteRecursive($group['id']);
            if (empty($group['children'])) {
                unset($group['children']);
            }
            $data[] = $group;
        }
        return $data;
    }
    /**
     * build Query
     */
    public function buildQuery() {
        $tableDescription = (new ShopCategoryDescription)->getTable();

        //description
        $query = $this
            ->leftJoin($tableDescription, $tableDescription . '.category_id', $this->getTable() . '.id')
            ->where($tableDescription . '.lang', bc_get_locale());
        //search keyword
        if ($this->bc_keyword !='') {
            $query = $query->where(function ($sql) use($tableDescription){
                $sql->where($tableDescription . '.title', 'like', '%' . $this->bc_keyword . '%')
                ->orWhere($tableDescription . '.keyword', 'like', '%' . $this->bc_keyword . '%')
                ->orWhere($tableDescription . '.description', 'like', '%' . $this->bc_keyword . '%');
            });
        }

        $query = $query->where('status', 1);

        if ($this->bc_parent !== '') {
            $query = $query->where('parent', $this->bc_parent);
        }

        if ($this->bc_top !== 'all') {
            $query = $query->where('top', $this->bc_top);
        }

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
            //Use field "sort" if haven't above
            if (!$ckeckSort) {
                $query = $query->orderBy($this->getTable().'.sort', 'asc');
            }
            //Default, will sort id
            $query = $query->orderBy($this->getTable().'.id', 'desc');
        }

        return $query;
    }
}
