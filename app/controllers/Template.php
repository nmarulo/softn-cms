<?php
/**
 * Template.php
 */

namespace SoftnCMS\controllers;

use SoftnCMS\rute\Router;

/**
 * Class Template
 * @author Nicolás Marulanda P.
 */
abstract class Template {
    
    private $siteUrl;
    
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
}
