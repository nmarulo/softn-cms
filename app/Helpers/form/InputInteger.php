<?php
/**
 * InputInteger.php
 */

namespace SoftnCMS\Helpers\form;

use SoftnCMS\controllers\Sanitize;
use SoftnCMS\controllers\Validate;
use SoftnCMS\Helpers\form\inputs\builders\InputNumberBuilder;
use SoftnCMS\Helpers\form\inputs\InputInterface;
use SoftnCMS\Helpers\form\inputs\types\InputNumber;

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
        $this->value     = $builder->value;
        $this->name      = $builder->name;
        $this->type      = $builder->type;
        $this->method    = $builder->method;
        $this->lenMax    = $builder->lenMax;
        $this->require   = $builder->require;
        $this->lenMin    = $builder->lenMin;
        $this->lenStrict = $builder->lenStrict;
    }
    
    public function filter() {
        $output = Sanitize::integer($this->value);
        
        if (!Validate::integer($output, $this->lenMax, $this->lenStrict)) {
            $output = '';
        }
        
        return $output;
    }
}
