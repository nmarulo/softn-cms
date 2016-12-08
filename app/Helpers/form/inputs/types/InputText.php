<?php
/**
 * InputText.php
 */

namespace SoftnCMS\helpers\form\inputs\types;

/**
 * Class InputText
 * @author Nicolás Marulanda P.
 */
trait InputText {
    
    use InputCommon;
    
    protected $accents      = TRUE;
    
    protected $withoutSpace = FALSE;
    
    protected $replaceSpace = '-';
    
    /**
     * @return boolean
     */
    public function isAccents() {
        return $this->accents;
    }
    
    /**
     * @return boolean
     */
    public function isWithoutSpace() {
        return $this->withoutSpace;
    }
    
    /**
     * @return string
     */
    public function getReplaceSpace() {
        return $this->replaceSpace;
    }
}
