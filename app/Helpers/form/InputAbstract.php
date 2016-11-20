<?php
/**
 * InputAbstract.php
 */

namespace SoftnCMS\Helpers\form;

/**
 * Class Input
 * @author Nicolás Marulanda P.
 */
abstract class InputAbstract {
    
    /** @var string */
    protected $value;
    
    /** @var string */
    protected $name;
    
    /** @var string */
    protected $type;
    
    protected $lenMax       = 0;
    
    protected $require      = TRUE;
    
    protected $lenMin       = 1;
    
    protected $arrayType    = '';
    
    protected $lenStrict    = FALSE;
    
    protected $accents      = TRUE;
    
    protected $withoutSpace = FALSE;
    
    protected $replaceSpace = '-';
    
    protected $sign         = FALSE;
    
    /** @var array $_POST o $_GET */
    protected $method = [];
    
}
