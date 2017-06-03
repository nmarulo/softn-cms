<?php
/**
 * InputSelectNumber.php
 */

namespace SoftnCMS\util\form\inputs\types;

abstract class InputSelectNumber extends InputNumber {
    
    /*
     * Se llamara primero al constructor del "InputSelect" y
     * el constructor del "InputSelect" llamara al del "InputNumber".
     */
    use InputSelect;
}
