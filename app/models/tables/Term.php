<?php
/**
 * Term.php
 */

namespace SoftnCMS\models\tables;

use SoftnCMS\util\database\TableAbstract;

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
    private $termPostCount;
    
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
    public function getTermPostCount() {
        return $this->termPostCount;
    }
    
    /**
     * @param int $termPostCount
     */
    public function setTermPostCount($termPostCount) {
        $this->termPostCount = $termPostCount;
    }
    
}
