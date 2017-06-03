<?php
/**
 * InputSelectNumberBuilder.php
 */

namespace SoftnCMS\util\form\inputs\builders;

use SoftnCMS\util\form\inputs\types\InputSelectNumber;

/**
 * Class InputSelectBuilder
 * @author Nicolás Marulanda P.
 */
abstract class InputSelectNumberBuilder extends InputNumberBuilder {
    
    use InputSelectBuilder;
    
    /**
     * InputSelectNumberBuilder constructor.
     *
     * @param InputSelectNumber $input
     */
    public function __construct($input) {
        parent::__construct($input);
        $this->input = $input;
    }
}
