<?php
/**
 * InputEmail.php
 */

namespace SoftnCMS\helpers\form;

use SoftnCMS\controllers\Sanitize;
use SoftnCMS\controllers\Validate;
use SoftnCMS\helpers\form\inputs\builders\InputBuilder;
use SoftnCMS\helpers\form\inputs\Input;
use SoftnCMS\helpers\form\inputs\InputInterface;

/**
 * Class InputEmail
 * @author Nicolás Marulanda P.
 */
class InputEmail implements InputInterface {
    
    use Input;
    
    /**
     * InputEmail constructor.
     *
     * @param InputBuilder $builder
     */
    public function __construct($builder) {
        $this->value   = $builder->getValue();
        $this->name    = $builder->getName();
        $this->type    = $builder->getType();
        $this->method  = $builder->getMethod();
        $this->require = $builder->isRequire();
    }
    
    public function filter() {
        $output = Sanitize::email($this->value);
        
        if (!Validate::email($output)) {
            $output = '';
        }
        
        return $output;
    }
}
