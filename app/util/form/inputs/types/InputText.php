<?php
/**
 * InputText.php
 */

namespace SoftnCMS\util\form\inputs\types;

use SoftnCMS\util\form\inputs\InputTextInterface;

/**
 * Class InputText
 * @author Nicolás Marulanda P.
 */
abstract class InputText extends InputCommon implements InputTextInterface {
    
    protected $accents;
    
    protected $withoutSpace;
    
    protected $replaceSpace;
    
    protected $specialChar;
    
    /**
     * InputText constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->accents      = TRUE;
        $this->withoutSpace = FALSE;
        $this->replaceSpace = '-';
        $this->specialChar  = FALSE;
    }
    
    /**
     * @return bool
     */
    public function isSpecialChar() {
        return $this->specialChar;
    }
    
    /**
     * @param bool $specialChar
     */
    public function setSpecialChar($specialChar) {
        $this->specialChar = $specialChar;
    }
    
    /**
     * @return boolean
     */
    public function isAccents() {
        return $this->accents;
    }
    
    /**
     * @param bool $accents
     */
    public function setAccents($accents) {
        $this->accents = $accents;
    }
    
    /**
     * @return boolean
     */
    public function isWithoutSpace() {
        return $this->withoutSpace;
    }
    
    /**
     * @param bool $withoutSpace
     */
    public function setWithoutSpace($withoutSpace) {
        $this->withoutSpace = $withoutSpace;
    }
    
    /**
     * @return string
     */
    public function getReplaceSpace() {
        return $this->replaceSpace;
    }
    
    /**
     * @param string $replaceSpace
     */
    public function setReplaceSpace($replaceSpace) {
        $this->replaceSpace = $replaceSpace;
    }
}
