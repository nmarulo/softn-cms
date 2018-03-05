<?php

/**
 * SilverEngine  - PHP MVC framework
 *
 * @package   SilverEngine
 * @author    SilverEngine Team
 * @copyright 2015-2017
 * @license   MIT
 * @link      https://github.com/SilverEngine/Framework
 */

namespace Silver\Core;


/**
 *
 */
class Controller
{

    protected $modelname;
    protected $model;
    protected $obj;

    /**
     *    Access to model
     *
     * @return mixed
     */
    protected function model($model = false)
    {

        if ($model) {
            $this->resourceName = $model;
            $modelName = $model;
        } else {
            $modelName = $this->resourceName;
        }


        $path = ROOT . "App/Models/" . ucfirst($modelName) . "Model" . EXT;

        if ($modelName AND file_exists($path)) {
            $model = "\App\Models\\" . ucfirst($modelName) . 'Model';

            //            $this->model = new $model();

            return (object) new $model();

        } else {
            throw new \Exception(sprintf('%s model file not found', $modelName));
        }
    }

    protected function getFilter()
    {
        return $this->obj;
    }


    protected function array_to_object($array)
    {
        return (object)$array;
    }

    protected function object_to_array($object)
    {
        return (array)$object;
    }

    /**
     *    Helper include
     *
     * @return mixed
     */
    protected function helper($name)
    {
        $helper = "\App\helpers\\" . ucfirst($name);

        return new $helper();
    }
}
