<?php
/**
 * TemplateAbstract.php
 */

namespace SoftnCMS\models;

use SoftnCMS\rute\Router;

/**
 * Class Template
 * @author Nicolás Marulanda P.
 */
abstract class TemplateAbstract {
    
    private $siteUrl;
    
    private $connectionDB;
    
    /**
     * Template constructor.
     */
    public function __construct() {
        $this->siteUrl = Router::getSiteURL();
    }
    
    /**
     * @return string
     */
    public function getSiteUrl() {
        return $this->siteUrl;
    }
    
    public abstract function initRelationship();
    
    /**
     * @return mixed
     */
    public function getConnectionDB() {
        return $this->connectionDB;
    }
    
    /**
     * @param mixed $connectionDB
     */
    public function setConnectionDB($connectionDB) {
        $this->connectionDB = $connectionDB;
    }
    
}
