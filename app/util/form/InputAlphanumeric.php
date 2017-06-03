<?php
/**
 * InputAlphanumeric.php
 */

namespace SoftnCMS\util\form;

use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\inputs\InputInterface;
use SoftnCMS\util\form\inputs\types\InputText;
use SoftnCMS\util\Sanitize;
use SoftnCMS\util\Validate;

/**
 * Interface InputAlphanumeric
 * @author Nicolás Marulanda P.
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
        $output = Sanitize::alphanumeric($this->value, $this->accents, $this->withoutSpace, $this->replaceSpace);
        
        if (!Validate::alphanumeric($output, $this->lenMax, $this->accents, $this->lenStrict)) {
            $output = '';
        }
        
        return $output;
    }
    
}
