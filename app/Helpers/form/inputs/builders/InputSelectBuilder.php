<?php
/**
 * InputSelectBuilder.php
 */

namespace SoftnCMS\helpers\form\inputs\builders;

use SoftnCMS\helpers\form\inputs\types\InputSelect;

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
