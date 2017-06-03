<?php
/**
 * InputSelectText.php
 */

namespace SoftnCMS\util\form\inputs\types;

abstract class InputSelectText extends InputText {
    
    /*
     * Se llamara primero al constructor del "InputSelect" y
     * el constructor del "InputSelect" llamara al del "InputText".
     */
    use InputSelect;
}
