<?php
/**
 * InputUrl.php
 */

namespace SoftnCMS\util\form;

use SoftnCMS\util\form\builders\InputUrlBuilder;
use SoftnCMS\util\form\inputs\Input;
use SoftnCMS\util\form\inputs\InputInterface;
use SoftnCMS\util\Sanitize;
use SoftnCMS\util\Validate;

/**
 * Class InputUrl
 * @author Nicolás Marulanda P.
 */
class InputUrl implements InputInterface {
    
    use Input;
    
    /**
     * InputUrl constructor.
     *
     * @param InputUrlBuilder $builder
     */
    public function __construct($builder) {
        $this->value   = $builder->getValue();
        $this->name    = $builder->getName();
        $this->type    = $builder->getType();
        $this->method  = $builder->getMethod();
        $this->require = $builder->isRequire();
    }
    
    public function filter() {
        $output = Sanitize::url($this->value);
        
        if (!Validate::url($output)) {
            $output = '';
        }
        
        return $output;
    }
}
