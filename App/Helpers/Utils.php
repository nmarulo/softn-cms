<?php
/**
 * Utils.php
 */

namespace App\Helpers;

use App\Models\Settings;

/**
 * Class Utils
 * @author Nicolás Marulanda P.
 */
class Utils {
    
    public function dateNow($format = 'Y-m-d') {
        return date($format, time());
    }
    
    public function stringToDate($time, $format, $toFormat = 'Y-m-d') {
        return \DateTime::createFromFormat($format, $time)
                        ->format($toFormat);
    }
    
    public function getDateFormat() {
        $dateFormat = Settings::where('option_key', '=', 'setting_date_format')
                              ->first();
        
        if (!$dateFormat) {
            return 'Y-m-d';
        }
        
        return $dateFormat->option_value;
    }
}
