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
     * @param string $listType
     *
     * @return $this
     */
    public function setListType($listType) {
        $this->input->setListType($listType);
        
        return $this;
    }
}
