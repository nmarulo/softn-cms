<?php

namespace App\Helpers;

use App\Rest\Common\DataTable\Filter;
use Silver\Database\Model;
use Silver\Database\Query;

/**
 * SearchModelHelper Helper
 */
class SearchDataTableHelper {
    
    /** @var int */
    private $count;
    
    /** @var Query */
    private $query;
    
    /**
     * SearchModelHelper constructor.
     */
    public function __construct() {
        $this->count = -1;
    }
    
    /**
     * @param Model  $mode
     * @param Filter $filter
     * @param Query  $query
     *
     * @return SearchDataTableHelper
     */
    public function filter(Model $mode, Filter $filter, Query $query): SearchDataTableHelper {
        if ($filter == NULL) {
            return $this;
        }
        
        $matches      = [];
        $search       = $filter->value;//login_t:admin email:info@sofn.red
        $searchable   = $mode->getSearchable();
        $queryCount   = Query::count()
                             ->from($mode::tableName());
        $strictSearch = is_null($filter->strict) ? FALSE : $filter->strict;
        
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
                    $this->where($query, $column, $search, $strictSearch);
                    $this->where($queryCount, $column, $search, $strictSearch);
                }
            }
        }
        
        $count = $queryCount->single();
        
        if (is_numeric($count)) {
            $this->count = intval($count);
        }
        
        $this->query = $query;
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getCount(): int {
        return $this->count;
    }
    
    /**
     * @return Query
     */
    public function getQuery(): Query {
        return $this->query;
    }
    
    /**
     * @param Query   $query
     * @param array   $searchable
     * @param string  $string
     * @param boolean $strictSearch
     */
    private function searchFormat(Query &$query, array $searchable, string $string, bool $strictSearch): void {
        //pos 0: columna
        //pos 1: valor
        $explode     = explode(':', $string);
        $columnMatch = $explode[0];
        $value       = $explode[1];
        
        //compruebo que la columna existe.
        foreach ($searchable as $column) {
            if (!empty(preg_match(sprintf('/_%1$s$/', $columnMatch), $column))) {
                $this->where($query, $column, $value, $strictSearch);
                break;
            }
        }
    }
    
    private function where(Query &$query, $column, $value, bool $strict = TRUE): void {
        $op  = '=';
        $how = 'and';
        
        if (!$strict) {
            $how   = 'or';
            $op    = 'like';
            $value = sprintf('%1$s%2$s%1$s', '%', $value);
        }
        
        $query = $query->where($column, $op, $value, $how);
    }
    
}
