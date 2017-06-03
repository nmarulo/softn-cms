<?php
/**
 * InputSelectTextBuilder.php
 */

namespace SoftnCMS\util\form\inputs\builders;

use SoftnCMS\util\form\inputs\types\InputSelectText;

/**
 * Class InputSelectTextBuilder
 * @author Nicolás Marulanda P.
 */
abstract class InputSelectTextBuilder extends InputTextBuilder {
    
    use InputSelectBuilder;
    
    /**
     * InputSelectTextBuilder constructor.
     *
     * @param InputSelectText $input ;
     */
    public function __construct($input) {
        parent::__construct($input);
        $this->input = $input;
    }
}
