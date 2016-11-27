<?php
/**
 * InputEmail.php
 */

namespace SoftnCMS\Helpers\form;

use SoftnCMS\controllers\Sanitize;
use SoftnCMS\controllers\Validate;
use SoftnCMS\Helpers\form\inputs\builders\InputBuilder;
use SoftnCMS\Helpers\form\inputs\Input;
use SoftnCMS\Helpers\form\inputs\InputInterface;

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
