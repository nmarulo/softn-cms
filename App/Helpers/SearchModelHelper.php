<?php

namespace App\Helpers;

use Silver\Core\Bootstrap\Facades\Request;
use Silver\Database\Model;
use Silver\Database\Query;

/**
 * SearchModelHelper Helper
 */
class SearchModelHelper {
    
    /** @var int */
    private $count;
    
    /** @var Query */
    private $query;
    
    /**
     * SearchModelHelper constructor.
     */
    public function __construct() {
        $this->count = NULL;
        $this->query = NULL;
    }
    
    /**
     * @param Model $currentModel
     * @param Query $query
     *
     * @return $this
     */
    public function getInstance($currentModel, $query) {
        $matches      = [];
        $search       = Request::input('search-value');//login_t:admin email:info@sofn.red
        $searchable   = (new $currentModel())->getSearchable();
        $queryCount   = Query::count()
                             ->from($currentModel::tableName());
        $strictSearch = Request::input('search-strict', FALSE) == 'on';
        
        if (!empty($search)) {
            $explode = explode(' ', $search);
            
            if (count($explode) > 0 && !empty(preg_match_all('/[a-zA-Z_]+:/', $search, $matches))) {
                $countMatches = count($matches[0]);
                
                if ($countMatches > 1) {
                    for ($i = 1; $i < $countMatches; ++$i) {
                        //email:
                        $match  = $matches[0][$i];
                        $pos    = strpos($search, $match);
                        $match  = trim(substr($search, 0, $pos));
                        $search = substr($search, $pos);
                        $this->searchFormat($query, $searchable, $match, $strictSearch);
                        $this->searchFormat($queryCount, $searchable, $match, $strictSearch);
                    }
                }
                
                //ya que el ultimo no lo buscara, vuelvo a llamar al método
                $this->searchFormat($query, $searchable, $search, $strictSearch);
                $this->searchFormat($queryCount, $searchable, $search, $strictSearch);
            } else {
                foreach ($searchable as $column) {
                    $this->where($query, $column, $search, $strictSearch, 'or');
                    $this->where($queryCount, $column, $search, $strictSearch, 'or');
                }
            }
        }
        
        $this->count = $queryCount->single();
        $this->query = $query;
        
        return $this;
    }
    
    /**
     * @param Query   $query
     * @param array   $searchable
     * @param string  $string
     * @param boolean $strictSearch
     */
    private function searchFormat(&$query, $searchable, $string, $strictSearch) {
        //pos 0: columna
        //pos 1: valor
        $explode     = explode(':', $string);
        $columnMatch = $explode[0];
        $value       = $explode[1];
        
        $how = $strictSearch ? 'and' : 'or';
        
        //compruebo que la columna existe.
        foreach ($searchable as $column) {
            if (!empty(preg_match(sprintf('/_%1$s$/', $columnMatch), $column))) {
                $this->where($query, $column, $value, $strictSearch, $how);
                break;
            }
        }
    }
    
    private function where(&$query, $column, $value, $strict = TRUE, $how = 'and') {
        $op = '=';
        
        if (!$strict) {
            $op    = 'like';
            $value = sprintf('%1$s%2$s%1$s', '%', $value);
        }
        
        $query = $query->where($column, $op, $value, $how);
    }
    
    /**
     * @return int
     */
    public function getCount() {
        return $this->count;
    }
    
    /**
     * @return Query
     */
    public function getQuery() {
        return $this->query;
    }
    
}
