<?php
/**
 * Utils.php
 */

namespace App\Helpers;

use Silver\Core\Bootstrap\Facades\Request;

/**
 * Class Utils
 * @author Nicolás Marulanda P.
 */
class Utils {
    
    public function dateNow($format = 'Y-m-d H:i:s') {
        return date($format, time());
    }
    
    public function stringToDate($time, $format, $toFormat = 'Y-m-d H:m:s') {
        return \DateTime::createFromFormat($format, $time)
                        ->format($toFormat);
    }
    
    public function isRequestMethod($requestMethodName) {
        return Request::method() == strtolower($requestMethodName);
    }
    
}
