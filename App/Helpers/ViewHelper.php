<?php

namespace App\Helpers;

use Silver\Core\Bootstrap\Facades\Request;
use Silver\Database\Query;
use Silver\Http\View;
use App\Facades\Pagination;

/**
 * ViewHelper Helper
 */
class ViewHelper {
    
    /** @var View */
    private $view;
    
    public function make($template, $data = []) {
        $this->view = View::make($template, $data);
        
        return $this;
    }
    
    public function with($key, $value = TRUE) {
        $this->view->with($key, $value);
        
        return $this;
    }
    
    public function withComponent($value = TRUE, $key = FALSE) {
        $this->view->withComponent($value, $key);
        
        return $this;
    }
    
    public function pagination($currentModel, $nameModel, $dataModelClosure = NULL, $count = NULL) {
        $pagination = $this->getPaginationInstance($currentModel, $count);
        $dataModel  = $this->getDataModel($pagination, $currentModel, $dataModelClosure);
        $this->view->with($nameModel, $dataModel)
                   ->withComponent($pagination, 'pagination');
        
        return $this;
    }
    
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
    
    private function getDataModel($pagination, $currentModel, $dataModelClosure = NULL) {
        $numberRowShow = $pagination->getNumberRowShow();
        $beginRow      = $pagination->getBeginRow();
        
        if ($dataModelClosure == NULL || !is_callable($dataModelClosure)) {
            return $currentModel::query()
                                ->orderBy('id', 'desc')
                                ->limit($numberRowShow)
                                ->offset($beginRow)
                                ->all();
        }
        
        return $dataModelClosure($numberRowShow, $beginRow);
    }
    
    public function get() {
        return $this->view;
    }
}
