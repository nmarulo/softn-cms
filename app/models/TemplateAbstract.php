<?php
/**
 * TemplateAbstract.php
 */

namespace SoftnCMS\models;

use SoftnCMS\util\database\DBInterface;

/**
 * Class Template
 * @author Nicolás Marulanda P.
 */
abstract class TemplateAbstract {
    
    private $siteUrl;
    
    private $connectionDB;
    
    /**
     * Template constructor.
     *
     * @param string           $siteUrl
     * @param DBInterface|null $connectionDB
     */
    public function __construct($siteUrl = '', DBInterface $connectionDB = NULL) {
        $this->siteUrl      = $siteUrl;
        $this->connectionDB = $connectionDB;
    }
    
    /**
     * @return string
     */
    public function getSiteUrl() {
        return $this->siteUrl;
    }
    
    /**
     * @param string $siteUrl
     */
    public function setSiteUrl($siteUrl) {
        $this->siteUrl = $siteUrl;
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
