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
    
    /** @var mixed */
    private $query;
    
    /** @var Pagination */
    private $pagination;
    
    /** @var boolean */
    private $hasPagination;
    
    /** @var Model */
    private $model;
    
    /** @var SearchModelHelper */
    private $searchModel;
    
    /** @var \Closure */
    private $paginationDataClosure;
    
    /**
     * ViewHelper constructor.
     */
    public function __construct() {
        $this->query         = NULL;
        $this->pagination    = NULL;
        $this->model         = NULL;
        $this->searchModel   = NULL;
        $this->hasPagination = FALSE;
    }
    
    /**
     * @param Model $model
     *
     * @return $this
     */
    public function model($model, $query) {
        if (empty($query)) {
            $query = $model::query();
        }
        
        $this->query = $query;
        $this->model = $model;
        
        return $this;
    }
    
    public function search() {
        $this->searchModel = SearchModelFacade::getInstance($this->model, $this->query);
        $this->setQuery($this->searchModel->getQuery());
        
        return $this;
    }
    
    /**
     * @param Query $query
     */
    private function setQuery($query) {
        $this->query = $query;
    }
    
    /**
     * @param \Closure $dataModelClosure
     *
     * @return $this
     */
    public function pagination($dataModelClosure = NULL) {
        $this->hasPagination         = TRUE;
        $this->paginationDataClosure = $dataModelClosure;
        
        return $this;
    }
    
    /**
     * @return array
     */
    public function all() {
        //En caso de llamar al método "all" antes que "getPagination".
        //Solo es para instanciar "Pagination", en caso de que lo este previamente.
        $this->getPagination();
        
        return $this->query->all();
    }
    
    /**
     * @return Pagination
     */
    public function getPagination() {
        if ($this->hasPagination && empty($this->pagination)) {
            $this->instancePagination();
        }
        
        return $this->pagination;
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
        $this->paginationDataClosure($this->paginationDataClosure);
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
     * @param \Closure $dataModelClosure
     */
    private function paginationDataClosure($dataModelClosure = NULL) {
        $query         = $this->query;
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
     * @return $this
     */
    public function sort() {
        $sortColumn = Request::input('sortColumn');
        $query      = $this->query;
        
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
