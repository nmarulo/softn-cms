<?php
/**
 * InputSelectTextBuilder.php
 */

namespace SoftnCMS\util\form\inputs\builders;

use SoftnCMS\util\form\inputs\types\InputSelect;
use SoftnCMS\util\form\inputs\types\InputSelectText;

/**
 * Class InputSelectTextBuilder
 * @author Nicolás Marulanda P.
 */
abstract class InputSelectTextBuilder extends InputTextBuilder {
    
    use InputSelectText;
    use InputSelectBuilder;
    
}
