<?php
/**
 * InputListAlphabetic.php
 */

namespace SoftnCMS\helpers\form;

use SoftnCMS\helpers\form\inputs\builders\InputSelectTextBuilder;
use SoftnCMS\helpers\form\inputs\InputInterface;
use SoftnCMS\helpers\form\inputs\types\InputSelectText;

/**
 * Class InputListAlphabetic
 * @author Nicolás Marulanda P.
 */
class InputListAlphabetic implements InputInterface {
    
    use InputSelectText;
    
    /**
     * InputListAlphabetic constructor.
     *
     * @param InputSelectTextBuilder $builder
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
        $this->listType     = $builder->getListType();
    }
    
    public function filter() {
        //TODO: pendiente
        return $this->value;
    }
}
