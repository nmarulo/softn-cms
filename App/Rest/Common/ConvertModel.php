<?php
/**
 * ConvertObject.php
 */

namespace App\Rest\Common;

use Silver\Database\Model;

/**
 * Interface ConvertObject
 * @author Nicolás Marulanda P.
 */
interface ConvertModel {
    
    /**
     * @param mixed $object
     * @param bool  $hideProps
     *
     * @return mixed
     * @throws \Exception
     */
    public static function convertToModel($object, bool $hideProps = TRUE);
    
    /**
     * @param Model $model
     * @param bool  $hideProps
     *
     * @return mixed
     * @throws \Exception
     */
    public static function convertOfModel(Model $model, bool $hideProps = TRUE);
    
}
