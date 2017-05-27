<?php
/**
 * TableAbstract.php
 */

namespace SoftnCMS\models;

/**
 * Class TableAbstract
 * @author Nicolás Marulanda P.
 */
abstract class TableAbstract {
    
    /** @var int */
    private $id;
    
    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }
    
}
