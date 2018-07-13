<?php

namespace App\Helpers;

use Silver\Core\Bootstrap\Facades\Request;
use Silver\Database\Model;
use Silver\Database\Query;

/*
 * ModelHelper Helper
 */

class ModelHelper {
    
    /** @var Query */
    private $query;
    
    /** @var Pagination */
    private $pagination;
    
    /** @var Model */
    private $model;
    
    /**
     * ViewHelper constructor.
     */
    public function __construct() {
        $this->query      = NULL;
        $this->pagination = NULL;
        $this->model      = NULL;
    }
    
    /**
     * @param Model $model
     *
     * @return $this
     */
    public function model($model) {
        $this->model = $model;
        
        return $this;
    }
    
    /**
     * @param \Closure $dataModelClosure
     * @param int      $count
     *
     * @return $this
     */
    public function pagination($dataModelClosure = NULL, $count = NULL) {
        $this->pagination = $this->getPaginationInstance($count);
        $query            = $this->getQuery();
        $query            = $this->getDataModel($query, $dataModelClosure);
        
        $this->setQuery($query);
        
        return $this;
    }
    
    /**
     * @param Model $currentModel
     * @param int   $count
     *
     * @return mixed
     */
    private function getPaginationInstance($count = NULL) {
        $currentPage = 1;
        $totalData   = $count;
        
        if (Request::ajax()) {
            $currentPage = Request::input('page', 1);
        }
        
        if ($count == NULL) {
            $totalData = Query::count()
                              ->from($this->getTableName())
                              ->single();
        }
        
        return \App\Facades\Pagination::getInstance($currentPage, $totalData);
    }
    
    private function getTableName() {
        $model = $this->model;
        
        return $model::tableName();
    }
    
    /**
     * @param Model $currentModel
     *
     * @return mixed
     */
    private function getQuery() {
        $model = $this->model;
        
        if (empty($this->query)) {
            $this->query = $model::query();
        }
        
        return $this->query;
    }
    
    /**
     * @param mixed    $query
     * @param \Closure $dataModelClosure
     *
     * @return Query
     */
    private function getDataModel($query, $dataModelClosure = NULL) {
        $numberRowShow = $this->pagination->getNumberRowShow();
        $beginRow      = $this->pagination->getBeginRow();
        
        if ($dataModelClosure == NULL || !is_callable($dataModelClosure)) {
            return $query->limit($numberRowShow)
                         ->offset($beginRow);
        }
        
        return $dataModelClosure($query, $numberRowShow, $beginRow);
    }
    
    /**
     * @param Query $query
     */
    private function setQuery($query) {
        $this->query = $query;
    }
    
    /**
     * @return array
     */
    public function all() {
        return $this->query->all();
    }
    
    /**
     * @return Pagination
     */
    public function getPagination() {
        return $this->pagination;
    }
    
    /**
     * @return $this
     */
    public function sort() {
        $sortColumn = Request::input('sortColumn');
        $query      = $this->getQuery();
        
        if (Request::ajax()) {
            if (!empty($sortColumn)) {
                $sortColumn = (array)json_decode($sortColumn);
                
                if (is_array($sortColumn)) {
                    
                    foreach ($sortColumn as $value) {
                        $value = (array)$value;
                        
                        if (empty($value['column']) || empty($value['sort'])) {
                            continue;
                        }
                        
                        $query = $query->orderBy($value['column'], $value['sort']);
                    }
                    
                }
            }
        }
        
        $this->setQuery($query);
        
        return $this;
    }
    
}
