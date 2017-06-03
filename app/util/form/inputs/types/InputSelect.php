<?php
/**
 * InputSelect.php
 */

namespace SoftnCMS\util\form\inputs\types;

/**
 * Class InputSelect
 * @author Nicolás Marulanda P.
 */
trait InputSelect {
    
    protected $listType = 'integer';
    
    /**
     * @return string
     */
    public function getListType() {
        return $this->listType;
    }
    
}
