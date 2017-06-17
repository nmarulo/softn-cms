<?php
/**
 * Template.php
 */

namespace SoftnCMS\controllers;

use SoftnCMS\models\managers\OptionsManager;

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
        $optionsManager = new OptionsManager();
        $this->siteUrl  = $optionsManager->getSiteUrl();
    }
    
    /**
     * @return string
     */
    public function getSiteUrl() {
        return $this->siteUrl;
    }
    
    public abstract function initRelationship();
}
