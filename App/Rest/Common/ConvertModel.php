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
     * @param array|BaseDTO $object
     * @param bool          $hideProps
     *
     * @return array|BaseDTO
     * @throws \Exception
     */
    public static function convertToModel($object, bool $hideProps = TRUE);
    
    /**
     * @param array|Model $object
     * @param bool        $hideProps
     *
     * @return array|Model
     * @throws \Exception
     */
    public static function convertOfModel($object, bool $hideProps = TRUE);
    
}
