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
     * InputSelect constructor.
     */
    public function __construct() {
        /*
         * NOTA: Si el que implementa "InputSelect", no tiene padre
         * se lanzara un error fatal.
         */
        parent::__construct();
        $this->listType = 'integer';
    }
    
    /**
     * @return string
     */
    public function getListType() {
        return $this->listType;
    }
    
    /**
     * @param string $listType
     */
    public function setListType($listType) {
        $this->listType = $listType;
    }
    
}
