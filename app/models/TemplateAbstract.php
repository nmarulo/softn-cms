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
