<?php

namespace App\Helpers;

use App\Facades\SearchDataTableFacade;
use App\Rest\Common\DataTable\DataTable;
use App\Rest\Common\DataTable\SortColumn;
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
    
    /** @var \Closure */
    private $paginationDataClosure;
    
    /**
     * @var int
     */
    private $count;
    
    /**
     * @var DataTable
     */
    private $dataTable;
    
    /**
     * ViewHelper constructor.
     */
    public function __construct() {
        $this->query         = NULL;
        $this->pagination    = NULL;
        $this->model         = "";
        $this->hasPagination = FALSE;
        $this->count         = -1;
    }
    
    /**
     * @deprecated Usar Utils::parseOf()
     *
     * @param $array
     * @param $model
     *
     * @return mixed
     */
    public function arrayToObject($array, $model) {
        $obj = new $model();
        
        foreach ($array as $key => $value) {
            $obj->{$key} = $value;
        }
        
        return $obj;
    }
    
    /**
     * @param string         $model
     * @param DataTable|null $dataTable
     *
     * @return $this
     * @throws \Exception
     */
    public function model(string $model, ?DataTable $dataTable = NULL) {
        $object = new $model();
        
        if (!($object instanceof Model)) {
            throw new \Exception("La clase no es una instancia de Model");
        }
        
        $this->query     = $object::query();
        $this->model     = $object;
        $this->dataTable = $dataTable;
        
        return $this;
    }
    
    public function search() {
        if ($this->dataTable == NULL || $this->dataTable->filter == NULL) {
            return $this;
        }
        
        $searchDataTable = SearchDataTableFacade::filter($this->model, $this->dataTable->filter, $this->query);
        $this->setQuery($searchDataTable->getQuery());
        $this->count = $searchDataTable->getCount();
        
        return $this;
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
    
    /**
     * @return $this
     */
    public function sort() {
        if ($this->dataTable == NULL) {
            return $this;
        }
        
        $sortColumn = $this->dataTable->sortColumn;
        $query      = $this->query;
        
        if (!empty($sortColumn)) {
            foreach ($sortColumn as $value) {
                if (!($value instanceof SortColumn) && empty($value->name)) {
                    continue;
                }
                
                if (is_null($value->key)) {
                    $value->key = 'asc';
                }
                
                $query = $query->orderBy($value->name, $value->key);
            }
        }
        
        $this->setQuery($query);
    
        return $this;
    }
    
    /**
     * @param Query $query
     */
    private function setQuery(Query $query) {
        $this->query = $query;
    }
    
    private function instancePagination() {
        $currentPage = 1;
        
        if ($this->dataTable != NULL) {
            $currentPage = $this->dataTable->page;
        }
        
        $totalData = $this->count;
        
        if ($totalData == -1) {
            $totalData = Query::count()
                              ->from($this->getTableName())
                              ->single();
        }
        $this->pagination = \App\Facades\Pagination::getInit($currentPage, $totalData);
        $this->paginationDataClosure($this->paginationDataClosure);
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
        $numberRowShow = $this->pagination->numberRowShow;
        $beginRow      = $this->pagination->beginRow;
        
        if ($dataModelClosure == NULL || !is_callable($dataModelClosure)) {
            $query = $query->limit($numberRowShow)
                           ->offset($beginRow);
        } else {
            $query = $dataModelClosure($query, $numberRowShow, $beginRow);
        }
        
        $this->setQuery($query);
    }
    
}
