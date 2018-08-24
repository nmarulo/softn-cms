<?php
/**
 * Page.php
 */

namespace App\Helpers;

/**
 * Class Page
 * @author Nicolás Marulanda P.
 */
class Page implements \JsonSerializable {
    
    /** @var string */
    private $styleClass;
    
    /** @var string */
    private $value;
    
    /** @var array */
    private $attrData;
    
    public function __construct($value, $styleClass = "", $attrData = []) {
        $this->styleClass = $styleClass;
        $this->value      = $value;
        $this->attrData = $attrData;
    }
    
    public function attrToString() {
        $attr = array_map(function($key, $value){
            return "data-${key}='${value}'";
        }, array_keys($this->attrData), $this->attrData);
        
        return implode(' ', $attr);
    }
    
    public function jsonSerialize() {
        return [
                'styleClass' => $this->styleClass,
                'value'      => $this->value,
                'attrData'   => $this->attrData,
        ];
    }
    
    
    /**
     * @return array
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
