<?php
/**
 * InputUrl.php
 */

namespace SoftnCMS\helpers\form;

use SoftnCMS\controllers\Sanitize;
use SoftnCMS\controllers\Validate;
use SoftnCMS\helpers\form\builders\InputUrlBuilder;
use SoftnCMS\helpers\form\inputs\Input;
use SoftnCMS\helpers\form\inputs\InputInterface;

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
