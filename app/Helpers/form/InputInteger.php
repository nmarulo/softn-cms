<?php
/**
 * InputInteger.php
 */

namespace SoftnCMS\helpers\form;

use SoftnCMS\controllers\Sanitize;
use SoftnCMS\controllers\Validate;
use SoftnCMS\helpers\form\inputs\builders\InputNumberBuilder;
use SoftnCMS\helpers\form\inputs\InputInterface;
use SoftnCMS\helpers\form\inputs\types\InputNumber;

/**
 * Class InputInteger
 * @author Nicolás Marulanda P.
 */
class InputInteger implements InputInterface {
    
    use InputNumber;
    
    /**
     * InputInteger constructor.
     *
     * @param InputNumberBuilder $builder
     */
    public function __construct($builder) {
        $this->value     = $builder->getValue();
        $this->name      = $builder->getName();
        $this->type      = $builder->getType();
        $this->method    = $builder->getMethod();
        $this->lenMax    = $builder->getLenMax();
        $this->require   = $builder->isRequire();
        $this->lenMin    = $builder->getLenMin();
        $this->lenStrict = $builder->isLenStrict();
        $this->sign      = $builder->isSign();
        
    }
    
    public function filter() {
        $output = Sanitize::integer($this->value, $this->sign);
        
        if (!Validate::integer($output, $this->lenMax, $this->lenStrict)) {
            $output = '';
        }
        
        return $output;
    }
}
