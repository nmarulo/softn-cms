<?php
/**
 * InputSelectBuilder.php
 */

namespace SoftnCMS\Helpers\form\inputs\builders;

use SoftnCMS\Helpers\form\inputs\types\InputSelect;

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
