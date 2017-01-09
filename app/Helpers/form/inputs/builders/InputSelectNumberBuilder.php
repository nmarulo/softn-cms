<?php
/**
 * InputSelectNumberBuilder.php
 */

namespace SoftnCMS\helpers\form\inputs\builders;

use SoftnCMS\helpers\form\inputs\types\InputSelect;

/**
 * Class InputSelectBuilder
 * @author Nicolás Marulanda P.
 */
abstract class InputSelectNumberBuilder extends InputNumberBuilder {
    
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
