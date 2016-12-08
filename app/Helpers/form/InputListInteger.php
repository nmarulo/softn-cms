<?php
/**
 * InputListInteger.php
 */

namespace SoftnCMS\helpers\form;

use SoftnCMS\controllers\Sanitize;
use SoftnCMS\helpers\form\inputs\builders\InputSelectNumberBuilder;
use SoftnCMS\helpers\form\inputs\InputInterface;
use SoftnCMS\helpers\form\inputs\types\InputSelectNumber;

/**
 * Class InputListInteger
 * @author Nicolás Marulanda P.
 */
class InputListInteger implements InputInterface {
    
    use InputSelectNumber;
    
    /**
     * InputListInteger constructor.
     *
     * @param InputSelectNumberBuilder $builder
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
        $this->listType  = $builder->getListType();
    }
    
    public function filter() {
        return Sanitize::arrayList($this->value, $this->listType, $this->sign);
    }
}
