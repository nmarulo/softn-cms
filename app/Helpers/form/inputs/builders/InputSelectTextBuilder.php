<?php
/**
 * InputSelectTextBuilder.php
 */

namespace SoftnCMS\Helpers\form\inputs\builders;

use SoftnCMS\Helpers\form\inputs\types\InputSelect;
use SoftnCMS\Helpers\form\inputs\types\InputSelectText;

/**
 * Class InputSelectTextBuilder
 * @author Nicolás Marulanda P.
 */
abstract class InputSelectTextBuilder extends InputTextBuilder {
    
    use InputSelectText;
    use InputSelectBuilder;
    
}
