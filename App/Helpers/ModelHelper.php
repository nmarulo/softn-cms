<?php

namespace App\Helpers;

use App\Facades\SearchModelFacade;
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
    
    /** @var SearchModelHelper */
    private $searchModel;
    
    /**
     * ViewHelper constructor.
     */
    public function __construct() {
        $this->query       = NULL;
        $this->pagination  = NULL;
        $this->model       = NULL;
        $this->searchModel = NULL;
    }
    
    /**
     * @param Model   $model
     * @param boolean $searchModel
     *
     * @return $this
     */
    public function model($model, $searchModel = FALSE) {
        $this->model = $model;
        
        if ($searchModel) {
            $this->searchModel = SearchModelFacade::model($model);
            $this->query       = $this->searchModel->getQuery();
        }
        
        return $this;
    }
    
    /**
     * @param \Closure $dataModelClosure
     *
     * @return $this
     */
    public function pagination($dataModelClosure = NULL) {
        $this->instancePagination();
        $this->paginationDataClosure($this->getQuery(), $dataModelClosure);
        
        return $this;
    }
    
    private function instancePagination() {
        $currentPage = 1;
        $totalData   = $this->getTotalNumDataSearchModel();
        
        if (Request::ajax()) {
            $currentPage = Request::input('page', 1);
        }
        
        if ($totalData == NULL) {
            $totalData = Query::count()
                              ->from($this->getTableName())
                              ->single();
        }
        
        $this->pagination = \App\Facades\Pagination::getInstance($currentPage, $totalData);
    }
    
    private function getTotalNumDataSearchModel() {
        if (empty($this->searchModel)) {
            return NULL;
        }
        
        return $this->searchModel->getCount();
    }
    
    private function getTableName() {
        $model = $this->model;
        
        return $model::tableName();
    }
    
    /**
     * @param mixed    $query
     * @param \Closure $dataModelClosure
     */
    private function paginationDataClosure($query, $dataModelClosure = NULL) {
        $numberRowShow = $this->pagination->getNumberRowShow();
        $beginRow      = $this->pagination->getBeginRow();
        
        if ($dataModelClosure == NULL || !is_callable($dataModelClosure)) {
            $query = $query->limit($numberRowShow)
                           ->offset($beginRow);
        } else {
            $query = $dataModelClosure($query, $numberRowShow, $beginRow);
        }
        
        $this->setQuery($query);
    }
    
    /**
     * @param Query $query
     */
    private function setQuery($query) {
        $this->query = $query;
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
        
        if (Request::ajax() && !empty($sortColumn)) {
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
        
        $this->setQuery($query);
        
        return $this;
    }
    
}
