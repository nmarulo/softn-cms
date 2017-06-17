<?php
/**
 * Option.php
 */

namespace SoftnCMS\models\tables;

use SoftnCMS\models\TableAbstract;

/**
 * Class Option
 * @author Nicolás Marulanda P.
 */
class Option extends TableAbstract {
    
    /** @var string */
    private $optionName;
    
    /** @var string */
    private $optionValue;
    
    /**
     * @return string
     */
    public function getOptionName() {
        return $this->optionName;
    }
    
    /**
     * @param string $optionName
     */
    public function setOptionName($optionName) {
        $this->optionName = $optionName;
    }
    
    /**
     * @return string
     */
    public function getOptionValue() {
        return $this->optionValue;
    }
    
    /**
     * @param string $optionValue
     */
    public function setOptionValue($optionValue) {
        $this->optionValue = $optionValue;
    }
    
}
