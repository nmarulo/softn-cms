<?php
/**
 * InputTextInterface.php
 */

namespace SoftnCMS\util\form\inputs;

/**
 * Interface InputTextInterface
 * @author Nicolás Marulanda P.
 */
interface InputTextInterface {
    
    /**
     * @param boolean $accents
     */
    public function setAccents($accents);
    
    /**
     * @param boolean $withoutSpace
     */
    public function setWithoutSpace($withoutSpace);
    
    /**
     * @param string $replaceSpace
     */
    public function setReplaceSpace($replaceSpace);
    
    /**
     * @param $specialChar
     */
    public function setSpecialChar($specialChar);
}
