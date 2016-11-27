<?php
/**
 * InputAlphanumeric.php
 */

namespace SoftnCMS\Helpers\form;

use SoftnCMS\controllers\Sanitize;
use SoftnCMS\controllers\Validate;
use SoftnCMS\Helpers\form\builders\InputAlphanumericBuilder;
use SoftnCMS\Helpers\form\inputs\InputInterface;
use SoftnCMS\Helpers\form\inputs\types\InputText;

/**
 * Interface InputAlphanumeric
 * @author MaruloPC-Desk
 */
class InputAlphanumeric implements InputInterface {
    
    use InputText;
    
    /**
     * Constructor.
     *
     * @param InputAlphanumericBuilder $builder
     */
    public function __construct($builder) {
        $this->value        = $builder->getValue();
        $this->name         = $builder->getName();
        $this->type         = $builder->getType();
        $this->method       = $builder->getMethod();
        $this->lenMax       = $builder->getLenMax();
        $this->require      = $builder->isRequire();
        $this->lenMin       = $builder->getLenMin();
        $this->lenStrict    = $builder->isLenStrict();
        $this->accents      = $builder->isAccents();
        $this->withoutSpace = $builder->isWithoutSpace();
        $this->replaceSpace = $builder->getReplaceSpace();
    }
    
    public function filter() {
        $output = Sanitize::alphanumeric($this->value);
        
        if (!Validate::alphanumeric($output, $this->lenMax, $this->accents, $this->lenStrict)) {
            $output = '';
        }
        
        return $output;
    }
    
}
