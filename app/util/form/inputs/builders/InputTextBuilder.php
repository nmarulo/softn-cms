<?php
/**
 * InputTextBuilder.php
 */

namespace SoftnCMS\util\form\inputs\builders;

use SoftnCMS\util\form\inputs\InputTextInterface;
use SoftnCMS\util\form\inputs\types\InputText;

/**
 * Class InputTextBuilder
 * @author Nicolás Marulanda P.
 */
abstract class InputTextBuilder extends InputCommonBuilder implements InputTextInterface {
    
    /**
     * @var InputText
     */
    private $input;
    
    /**
     * InputTextBuilder constructor.
     *
     * @param InputText $input
     */
    public function __construct(InputText $input) {
        parent::__construct($input);
        $this->input = $input;
    }
    
    /**
     * @param boolean $accents
     *
     * @return $this
     */
    public function setAccents($accents) {
        $this->input->setAccents($accents);
        
        return $this;
    }
    
    /**
     * @param boolean $withoutSpace
     *
     * @return $this
     */
    public function setWithoutSpace($withoutSpace) {
        $this->input->setWithoutSpace($withoutSpace);
        
        return $this;
    }
    
    /**
     * @param string $replaceSpace
     *
     * @return $this
     */
    public function setReplaceSpace($replaceSpace) {
        $this->input->setReplaceSpace($replaceSpace);
        
        return $this;
    }
    
    /**
     * @param $specialChar
     *
     * @return $this
     */
    public function setSpecialChar($specialChar) {
        $this->input->setSpecialChar($specialChar);
        
        return $this;
    }
    
}
