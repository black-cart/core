<?php

namespace BlackCart\Core\Front\Models;

/**
 * Trait Model.
 */
trait ModelTrait
{

    protected  $bc_limit = 'all'; // all or interger
    protected  $bc_paginate = 0; // 0: dont paginate,
    protected  $bc_sort = [];
    protected  $bc_moreWhere = []; // more wehere
    protected  $bc_random = 0; // 0: no random, 1: random
    protected  $bc_keyword = ''; // search search product
    protected  $bc_min_price = ''; // search min price
    protected  $bc_max_price = ''; // search max price
    protected  $bc_attribute = ''; // search attribute product

    
    /**
     * Set price beetween
     *
     */
    public function setPriceBetween($min,$max) {
        $this->bc_max_price = $max;
        $this->bc_min_price = $min;
        return $this;
    }
    /**
     * Set value limit
     * @param   [string]  $limit 
     */
    public function setLimit($limit) {
        if ($limit === 'all') {
            $this->bc_limit = $limit;
        } else {
            $this->bc_limit = (int)$limit;
        }
        return $this;
    }

    /**
     * Set value sort
     * @param   [array]  $sort ['field', 'asc|desc']
     */
    public function setSort(array $sort) {
        if (is_array($sort)) {
            $this->bc_sort[] = $sort;
        }
        return $this;
    }

    /**
     * Add more where
     * @param   [array]  $moreWhere 
     */
    public function setMoreWhere(array $moreWhere) {
        if (is_array($moreWhere)) {
            if (count($moreWhere) == 2) {
                $where[0] = $moreWhere[0];
                $where[1] = '=';
                $where[2] = $moreWhere[1];
            } elseif (count($moreWhere) == 3) {
                $where = $moreWhere;
            }
            if (count($where) == 3) {
                $this->bc_moreWhere[] = $where;
            }
        }
        return $this;
    }

    /**
     * Enable paginate mode
     *  0 - no paginate
     */
    public function setPaginate() {
        $this->bc_paginate = 1;
        return $this;
    }

    /**
     * Set random mode
     */
    public function setRandom() {
        $this->bc_random = 1;
        return $this;
    }
    
    /**
     * Set keyword search
     * @param   [string]  $keyword 
     */
    public function setKeyword($keyword) {
        if (trim($keyword)) {
            $this->bc_keyword = trim($keyword);
        }
        return $this;
    }

    /**
     * Set attribute search
     * @param   [string]  $keyword 
     */
    public function setAttributes($code) {
        if (trim($code)) {
            $this->bc_attribute = trim($code);
        }
        return $this;
    }
     /**
     * Get Sql
     */
    public function getSql() {
        $query = $this->buildQuery();
        if (!$this->bc_paginate) {
            if ($this->bc_limit !== 'all') {
                $query = $query->limit($this->bc_limit);
            }
        }
		return $query = $query->toSql();
    }

     /**
     * Get data
     * @param   [array]  $action 
     */
    public function getData(array $action = []) {
        $query = $this->buildQuery();
        if (!empty($action['query'])) {
            return $query;
        }
        if ($this->bc_paginate) {
            $data =  $query->paginate(($this->bc_limit === 'all') ? 20 : $this->bc_limit);
        } else {
            if ($this->bc_limit !== 'all') {
                $query = $query->limit($this->bc_limit);
            }
            $data = $query->get();
                
            if (!empty($action['keyBy'])) {
                $data = $data->keyBy($action['keyBy']);
            }
            if (!empty($action['groupBy'])) {
                $data = $data->groupBy($action['groupBy']);
            }

        }
        return $data;
    }

    /**
     * Get full data
     *
     * @return  [type]  [return description]
     */
    public function getFull() {
        if (method_exists($this, 'getDetail')) {
            return $this->getDetail($this->id);
        } else {
            return $this;
        }

    }

}
