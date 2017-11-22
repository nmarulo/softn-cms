<?php
/**
 * ControllerInterface.php
 */

namespace SoftnCMS\util\controller;

use SoftnCMS\util\database\DBInterface;

/**
 * Interface ControllerInterface
 * @author Nicolás Marulanda P.
 */
interface ControllerInterface {
    
    /**
     * @param DBInterface $connectionDB
     */
    public function setConnectionDB($connectionDB);
}
