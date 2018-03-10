<?php

namespace App\Helpers;

use Silver\Core\Bootstrap\Facades\Request;
use Silver\Core\Env;
use Silver\Database\Query;
use Silver\Exception\Exception;

/**
 * api Helper
 */
class Api {
    
    private static $counter = 0;
    
    public static function create($data, $code = NULL, $name = FALSE) {
        header('Content-Type: application/json');
        
        if ($name) {
            $class = '\\App\\Models\\' . ucfirst($name);
            
            if (!class_exists($class)) {
                return ('Model not exist with same class ' . $class);
            }
            
            $model  = new $class;
            $hidden = [];
            
            try {
                $hidden = $model->getHidden();
                $hidden = array_combine($hidden, $hidden);
            } catch (Exception $ex) {
            }
            
            foreach ($data as &$row) {
                $row = array_diff_key($row, $hidden);
            }
            
        }
        
        if ($code == '200' || $code == 200) {
            $payload = [
                'payload'       => $data,
                'code'          => $code,
                'count_all'     => self::$counter,
                'request_limit' => Env::get('API_REQ_LIMIT', 25),
                'version'       => Env::get('API_VERSION', '1.0.0.'),
                'debug'         => Env::get('API_DEBUG', FALSE),
            ];
        } else {
            $payload = [
                'errors'  => [
                    'message' => $data,
                    'check'   => TRUE,
                ],
                'code'    => $code,
                'version' => Env::get('API_VERSION', '1.0.0.'),
                'debug'   => Env::get('API_DEBUG', FALSE),
            ];
        }
        
        return $payload;
    }
    
    public static function counter($model_class, $id = FALSE, $table = FALSE) {
        $req = (object)Request::all();
        
        //select
        $q = Query::count();
        
        if ($table) {
            $q->from($table);
        } else {
            $q->from($model_class);
        }
        
        //        dd($id);
        
        //where
        if ($id) {
            if (is_array($id)) {
                $q->where($id[0], '=', $id[1]);
            } else {
                $q->where('id', '=', $id);
            }
        }
        
        if (isset($req->where)) {
            
            $filters = [
                'eq'  => '=',
                'neq' => '!=',
                'ls'  => '<',
                'mr'  => '>',
            ];
            
            $args = explode(',', $req->where);
            
            foreach ($args as $arg) {
                $find               = explode('::', $arg);
                $filter['arg']      = $find[0];
                $filter['operator'] = $op = $filters[$find[1]];;
                $filter['data'] = $find[2];
                
                $q->where($filter['arg'], $filter['operator'], $filter['data']);
            }
        }
        
        self::$counter = $q->single();
        
        return FALSE;
    }
    
    public static function all($table, $param = NULL) {
        $q = self::build($table);
        //        dd($q->all());
        //        exit;
        Query::select($param)
             ->from($table)
             ->all();
    }
    
    public static function build($model_class, $id = NULL, $table = FALSE) {
        $req = (object)Request::all();
        
        $api_limit = Env::get('API_REQ_LIMIT', 25);
        
        //select
        if (isset($req->select)) {
            $select = explode(',', $req->select);
            $q      = Query::select(...$select);
        } else {
            $q = Query::select();
        }
        
        if ($table) {
            $q->from($table);
        } else {
            $q->from($model_class);
        }
        
        //where
        if ($id) {
            if (is_array($id)) {
                $q->where($id[0], '=', $id[1]);
            } else {
                $q->where('id', '=', $id);
            }
        }
        
        if (isset($req->where)) {
            
            $filters = [
                'eq'  => '=',
                'neq' => '!=',
                'ls'  => '<',
                'mr'  => '>',
            ];
            
            $args = explode(',', $req->where);
            
            foreach ($args as $arg) {
                $find = explode('::', $arg);
                //                   ndd($find);
                $filter['arg']      = $find[0];
                $filter['operator'] = $op = $filters[$find[1]];;
                $filter['data'] = $find[2];
                
                $q->where($filter['arg'], $filter['operator'], $filter['data']);
            }
        }
        
        //sort
        if (isset($req->sort)) {
            $sort = explode("::", $req->sort);
            $q->orderBy($sort[0], $sort[1]);
        }
        
        //        dd($models);
        //  include model
        if (isset($req->include)) {
            $include_model = explode(',', $req->include);
            foreach ($include_model as $model) {
                
                if ($table) {
                    $class = '\\App\\Models\\' . ucfirst($table);
                } else {
                    $class = '\\App\\Models\\' . ucfirst($model_class);
                }
                
                if (!class_exists($class)) {
                    return ('Model not exist with same class name line 127 - please add var modelname in build ');
                }
                
                $model = new $class;
                
                foreach ($include_model as $m) {
                    if (in_array($m, $model->getIncludable())) {
                        $models[$m] = Query::select()
                                           ->from($m)
                                           ->where('id_user', 1)
                                           ->all();
                    }
                }
            }
        }
        
        if (!isset($req->page) || !isset($req->range)) {
            $models = $q->limit($api_limit);
        }
        
        if (isset($req->page)) {
            if ($req->page != 'all') {
                $page = explode(',', $req->page);
                ($page[1]) ? $limit = $page[1] : $limit = $api_limit;
                $models = $q->page($page[0], $limit);
            }
        }
        
        // set limit range
        if (isset($req->range)) {
            $range = explode(',', $req->range);
            //            ndd($limit);
            $models = $q->limit($range[0]);
            $models = $q->offset($range[1]);
        }
        
        if ($id) {
            if (is_array($id)) {
                $models = $q->all(\PDO::FETCH_ASSOC);
            } else {
                $models = $q->fetch(\PDO::FETCH_ASSOC);
            }
        } else {
            $models = $q->all(\PDO::FETCH_ASSOC);
        }
        
        return $models;
    }
    
    public static function get($table, $param = NULL) {
        Query::select()
             ->from($table)
             ->fetch();
    }
}
