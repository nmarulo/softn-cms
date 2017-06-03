<?php
/**
 * InputTextBuilder.php
 */

namespace SoftnCMS\util\form\inputs\builders;

use SoftnCMS\util\form\inputs\types\InputText;

/**
 * Class InputTextBuilder
 * @author Nicolás Marulanda P.
 */
abstract class InputTextBuilder extends InputCommonBuilder {
    
    use InputText;
    
    /**
     * @param boolean $accents
     *
     * @return $this
     */
    public function setAccents($accents) {
        $this->accents = $accents;
        
        return $this;
    }
    
    /**
     * @param boolean $withoutSpace
     *
     * @return $this
     */
    public function setWithoutSpace($withoutSpace) {
        $this->withoutSpace = $withoutSpace;
        
        return $this;
    }
    
    /**
     * @param string $replaceSpace
     *
     * @return $this
     */
    public function setReplaceSpace($replaceSpace) {
        $this->replaceSpace = $replaceSpace;
        
        return $this;
    }
    
}
