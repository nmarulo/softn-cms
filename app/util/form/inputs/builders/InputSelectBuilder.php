<?php
/**
 * InputSelectBuilder.php
 */

namespace SoftnCMS\util\form\inputs\builders;

use SoftnCMS\util\form\inputs\types\InputSelect;

trait InputSelectBuilder {
    
    /**
     * @var InputSelect
     */
    private $input;
    
    /**
     * InputSelectBuilder constructor.
     *
     * @param InputSelect $input
     */
    public function __construct(InputSelect $input) {
        parent::__construct($input);
        $this->input = $input;
    }
    
    /**
     * @param string $listType
     *
     * @return $this
     */
    public function setListType($listType) {
        $this->input->setListType($listType);
        
        return $this;
    }
}
