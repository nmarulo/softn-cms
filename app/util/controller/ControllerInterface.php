<?php
/**
 * ControllerInterface.php
 */

namespace SoftnCMS\util\controller;

use SoftnCMS\rute\Request;
use SoftnCMS\rute\Router;
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
    
    /**
     * @param Router $router
     */
    public function setRouter($router);
    
    /**
     * @param Request $request
     */
    public function setRequest($request);
}
