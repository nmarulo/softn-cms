<?php
/**
 * InputSelectTextBuilder.php
 */

namespace SoftnCMS\helpers\form\inputs\builders;

use SoftnCMS\helpers\form\inputs\types\InputSelect;
use SoftnCMS\helpers\form\inputs\types\InputSelectText;

/**
 * Class InputSelectTextBuilder
 * @author Nicolás Marulanda P.
 */
abstract class InputSelectTextBuilder extends InputTextBuilder {
    
    use InputSelectText;
    use InputSelectBuilder;
    
}
