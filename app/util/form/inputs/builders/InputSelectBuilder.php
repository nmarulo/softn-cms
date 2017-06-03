<?php
/**
 * InputSelectBuilder.php
 */

namespace SoftnCMS\util\form\inputs\builders;

use SoftnCMS\util\form\inputs\types\InputSelect;

trait InputSelectBuilder {
    
    use InputSelect;
    
    /**
     * @param string $listType
     *
     * @return $this
     */
    public function setListType($listType) {
        $this->listType = $listType;
        
        return $this;
    }
}
