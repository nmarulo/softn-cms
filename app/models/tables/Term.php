<?php
/**
 * Term.php
 */

namespace SoftnCMS\models\tables;

use SoftnCMS\models\TableAbstract;

/**
 * Class Term
 * @author Nicolás Marulanda P.
 */
class Term extends TableAbstract {
    
    /** @var string */
    private $termName;
    
    /** @var string */
    private $termDescription;
    
    /** @var int */
    private $termCount;
    
    /**
     * @return string
     */
    public function getTermName() {
        return $this->termName;
    }
    
    /**
     * @param string $termName
     */
    public function setTermName($termName) {
        $this->termName = $termName;
    }
    
    /**
     * @return string
     */
    public function getTermDescription() {
        return $this->termDescription;
    }
    
    /**
     * @param string $termDescription
     */
    public function setTermDescription($termDescription) {
        $this->termDescription = $termDescription;
    }
    
    /**
     * @return int
     */
    public function getTermCount() {
        return $this->termCount;
    }
    
    /**
     * @param int $termCount
     */
    public function setTermCount($termCount) {
        $this->termCount = $termCount;
    }
    
}
