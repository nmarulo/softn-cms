<?php
/**
 * Page.php
 */

namespace App\Helpers;

/**
 * Class Page
 * @author Nicolás Marulanda P.
 */
class Page {
    
    /** @var string */
    private $styleClass;
    
    /** @var string */
    private $value;
    
    /** @var string */
    private $attrData;
    
    public function __construct($value, $styleClass = "", $attrData = []) {
        $this->styleClass = $styleClass;
        $this->value      = $value;
        $this->setAttrData($attrData);
    }
    
    private function setAttrData($attrData) {
        $strAttrData = "";
        
        foreach ($attrData as $key => $value) {
            $strAttrData .= " data-$key='$value' ";
        }
        
        $this->attrData = $strAttrData;
    }
    
    /**
     * @return string
     */
    public function getAttrData() {
        return $this->attrData;
    }
    
    /**
     * @return mixed
     */
    public function getStyleClass() {
        return $this->styleClass;
    }
    
    /**
     * @return mixed
     */
    public function getValue() {
        return $this->value;
    }
    
}
