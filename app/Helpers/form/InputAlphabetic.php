<?php
/**
 * InputAlphabetic.php
 */

namespace SoftnCMS\helpers\form;

use SoftnCMS\controllers\Sanitize;
use SoftnCMS\controllers\Validate;
use SoftnCMS\helpers\form\builders\InputAlphabeticBuilder;
use SoftnCMS\helpers\form\inputs\InputInterface;
use SoftnCMS\helpers\form\inputs\types\InputText;

/**
 * Class InputAlphabetic
 * @author Nicolás Marulanda P.
 */
class InputAlphabetic implements InputInterface {
    
    use InputText;
    
    /**
     * InputAlphabetic constructor.
     *
     * @param InputAlphabeticBuilder $builder
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
        $output = Sanitize::alphabetic($this->value, $this->accents, $this->withoutSpace, $this->replaceSpace);
        
        if (!Validate::alphabetic($output, $this->lenMax, $this->accents, $this->lenStrict)) {
            $output = '';
        }
        
        return $output;
    }
    
}
