<?php
/**
 * InputText.php
 */

namespace SoftnCMS\util\form\inputs\types;

/**
 * Class InputText
 * @author Nicolás Marulanda P.
 */
abstract class InputText extends InputCommon {
    
    protected $accents      = TRUE;
    
    protected $withoutSpace = FALSE;
    
    protected $replaceSpace = '-';
    
    /**
     * InputText constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->accents      = TRUE;
        $this->withoutSpace = FALSE;
        $this->replaceSpace = '-';
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
