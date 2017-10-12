<?php
/**
 * InputCommonInterface.php
 */

namespace SoftnCMS\util\form\inputs;

/**
 * Class InputCommon
 * @author Nicolás Marulanda P.
 */
interface InputCommonInterface {
    
    /**
     * @param int $lenMax
     */
    public function setLenMax($lenMax);
    
    /**
     * @param int $lenMin
     */
    public function setLenMin($lenMin);
    
    /**
     * @param bool $lenStrict
     */
    public function setLenStrict($lenStrict);
}
