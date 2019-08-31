<?php

namespace App\Helpers;

use App\Facades\PaginationFacade;
use App\Facades\SearchDataTableFacade;
use App\Facades\UtilsFacade;
use App\Rest\Common\BaseSearch;
use App\Rest\Common\ConvertModel;
use App\Rest\Requests\DataTable\DataTable;
use App\Rest\Requests\DataTable\SortColumn;
use Silver\Database\Model;
use Silver\Database\Query;

/*
 * SearchHelper Helper
 */

class SearchHelper {
    
    /** @var mixed */
    private $query;
    
    /** @var PaginationHelper */
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
     * @param string $model
     *
     * @return $this
     * @throws \Exception
     */
    public function init(string $model) {
        $object = new $model();
        
        if (!($object instanceof Model)) {
            throw new \Exception("La clase no es una instancia de Model");
        }
        
        $this->query = $object::query();
        $this->model = $object;
        
        return $this;
    }
    
    /**
     * @param Model|BaseSearch|array $object
     * @param bool                   $strict
     *
     * @return SearchHelper
     * @throws \Exception
     */
    public function search($object = NULL, ?bool $strict = TRUE): SearchHelper {
        if (!$object) {
            return $this;
        }
        
        if (is_array($object)) {
            if ($strict && count($object) > 1) {
                $strict = FALSE;
            }
            
            foreach ($object as $item) {
                $this->search($item, $strict);
            }
            
            return $this;
        }
        
        $properties = [];
        $how        = $strict ? 'AND' : 'OR';
        $op         = '=';
        $sign       = '';
        
        if ($object instanceof Model) {
            $properties = $object->data();
        } elseif (UtilsFacade::isUseTrait($object, BaseSearch::class)) {
            if ($object instanceof ConvertModel) {
                $model = $object::convertToModel($object);
                
                if ($model instanceof Model) {
                    $properties = $model->data();
                }
            } else {
                $properties = $object->getProperties();
            }
            
            if (!$object->strict) {
                $op   = 'LIKE';
                $sign = '%';
            }
        } else {
            throw new \Exception("El objeto no es una instancia valida.");
        }
        
        foreach ($properties as $key => $value) {
            $this->query->where($key, $op, sprintf('%s%s%1$s', $sign, $value), $how);
        }
        
        return $this;
    }
    
    /**
     * @param DataTable|null $dataTable
     * @param \Closure       $dataModelClosure
     *
     * @return $this
     */
    public function dataTable(?DataTable $dataTable = NULL, $dataModelClosure = NULL) {
        if ($dataTable == NULL) {
            $dataTable = new DataTable();
        }
        
        $this->dataTable = $dataTable;
        
        $this->hasPagination         = TRUE;
        $this->paginationDataClosure = $dataModelClosure;
        
        if (!is_null($this->dataTable->filter)) {
            $searchDataTable = SearchDataTableFacade::filter($this->model, $this->dataTable->filter, $this->query);
            $this->setQuery($searchDataTable->getQuery());
            $this->count = $searchDataTable->getCount();
        }
        
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
     * @return PaginationHelper
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
        if ($this->dataTable == NULL || is_null($this->dataTable->sortColumn)) {
            return $this;
        }
        
        $query = $this->query;
        
        foreach ($this->dataTable->sortColumn as $value) {
            if (!($value instanceof SortColumn) && empty($value->name)) {
                continue;
            }
            
            if (is_null($value->key)) {
                $value->key = 'asc';
            }
            
            $query = $query->orderBy($value->name, $value->key);
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
        $totalData = $this->count;
        
        if ($totalData == -1) {
            $totalData = Query::count()
                              ->from($this->getTableName())
                              ->single();
        }
        
        $this->pagination = PaginationFacade::getInit($totalData, $this->dataTable);
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
