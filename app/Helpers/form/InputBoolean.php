<?php
/**
 * InputBoolean.php
 */

namespace SoftnCMS\helpers\form;

use SoftnCMS\controllers\Validate;
use SoftnCMS\helpers\form\inputs\builders\InputBuilder;
use SoftnCMS\helpers\form\inputs\Input;
use SoftnCMS\helpers\form\inputs\InputInterface;

/**
 * Class InputBoolean
 * @author Nicolás Marulanda P.
 */
class InputBoolean implements InputInterface {
    
    use Input;
    
    /**
     * InputBoolean constructor.
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
        return Validate::boolean($this->value);
    }
    
}
