<?php

namespace App\Helpers;

use Silver\Core\Bootstrap\Facades\Request;
use Silver\Database\Model;
use Silver\Database\Query;
use Silver\Http\View;
use App\Facades\Pagination;

/**
 * ViewHelper Helper
 */
class ViewHelper {
    
    /** @var View */
    private $view;
    
    /** @var array Key=tabla Value=Query */
    private $query;
    
    /**
     * ViewHelper constructor.
     */
    public function __construct() {
        $this->query = [];
    }
    
    public function make($template, $data = []) {
        $this->view = View::make($template, $data);
        
        return $this;
    }
    
    public function with($key, $value = TRUE) {
        $this->view->with($key, $value);
        
        return $this;
    }
    
    /**
     * @param Model          $currentModel
     * @param string|boolean $nameModel
     * @param \Closure       $dataModelClosure
     * @param int            $count
     *
     * @return $this
     */
    public function pagination($currentModel, $nameModel, $dataModelClosure = NULL, $count = NULL) {
        $pagination = $this->getPaginationInstance($currentModel, $count);
        $query      = $this->getQuery($currentModel, $nameModel);
        $query      = $this->getDataModel($pagination, $query, $dataModelClosure);
        
        $this->withComponent($pagination, 'pagination');
        $this->setQuery($currentModel, $query, $nameModel);
        
        return $this;
    }
    
    /**
     * @param Model $currentModel
     * @param int   $count
     *
     * @return mixed
     */
    private function getPaginationInstance($currentModel, $count = NULL) {
        $currentPage = 1;
        $totalData   = $count;
        
        if (Request::ajax()) {
            $currentPage = Request::input('page', 1);
        }
        
        if ($count == NULL) {
            $totalData = Query::count()
                              ->from($currentModel::tableName())
                              ->single();
        }
        
        return Pagination::getInstance($currentPage, $totalData);
    }
    
    /**
     * @param Model          $currentModel
     * @param string|boolean $nameModel
     *
     * @return mixed
     */
    private function getQuery($currentModel, $nameModel) {
        $nameModel = $this->checkAndGetNameModel($currentModel, $nameModel);
        
        if (!array_key_exists($nameModel, $this->query)) {
            $this->query[$nameModel] = $currentModel::query();
        }
        
        return $this->query[$nameModel];
    }
    
    /**
     * @param Model          $currentModel
     * @param string|boolean $nameModel
     *
     * @return mixed
     */
    private function checkAndGetNameModel($currentModel, $nameModel) {
        return empty($nameModel) ? $currentModel::tableName() : $nameModel;
    }
    
    /**
     * @param \App\Helpers\Pagination $pagination
     * @param mixed                   $query
     * @param \Closure                $dataModelClosure
     *
     * @return Query
     */
    private function getDataModel($pagination, $query, $dataModelClosure = NULL) {
        $numberRowShow = $pagination->getNumberRowShow();
        $beginRow      = $pagination->getBeginRow();
        
        if ($dataModelClosure == NULL || !is_callable($dataModelClosure)) {
            return $query->limit($numberRowShow)
                         ->offset($beginRow);
        }
        
        return $dataModelClosure($query, $numberRowShow, $beginRow);
    }
    
    public function withComponent($value, $key) {
        $this->view->withComponent($value, $key);
        
        return $this;
    }
    
    /**
     * @param Model          $currentModel
     * @param Query          $query
     * @param string|boolean $nameModel
     */
    private function setQuery($currentModel, $query, $nameModel) {
        $this->query[$this->checkAndGetNameModel($currentModel, $nameModel)] = $query;
    }
    
    /**
     * @return View
     */
    public function get() {
        foreach ($this->query as $key => $value) {
            $this->view->with($key, $value->all());
        }
        
        return $this->view;
    }
    
    /**
     * @param Model          $currentModel
     * @param string|boolean $nameModel
     *
     * @return $this
     */
    public function sort($currentModel, $nameModel = FALSE) {
        $sortColumn = Request::input('sortColumn');
        $query      = $this->getQuery($currentModel, $nameModel);
        
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
        
        $this->setQuery($currentModel, $query, $nameModel);
        
        return $this;
    }
}
