<?php
/**
 * InputHtml.php
 */

namespace SoftnCMS\util\form;

use SoftnCMS\util\Escape;
use SoftnCMS\util\form\builders\InputHtmlBuilder;
use SoftnCMS\util\form\inputs\InputInterface;
use SoftnCMS\util\form\inputs\types\InputText;

/**
 * Class InputHtml
 * @author Nicolás Marulanda P.
 */
class InputHtml implements InputInterface {
    
    use InputText;
    
    /**
     * InputHtml constructor.
     *
     * @param InputHtmlBuilder $builder
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
        return Escape::htmlEncode($this->value);
    }
}
