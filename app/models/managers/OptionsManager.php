<?php
/**
 * OptionsManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\classes\constants\OptionConstants;
use SoftnCMS\models\tables\Option;
use SoftnCMS\rute\Router;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\database\ManagerAbstract;
use SoftnCMS\util\Language;
use SoftnCMS\util\Util;

/**
 * Class OptionsManager
 * @author Nicolás Marulanda P.
 */
class OptionsManager extends ManagerAbstract {
    
    const TABLE        = 'options';
    
    const OPTION_NAME  = 'option_name';
    
    const OPTION_VALUE = 'option_value';
    
    public function getSiteUrl($router = NULL) {
        $siteUrl = '';
        
        if (!defined('INSTALL')) {
            $optionSiteUrl = $this->searchByName(OptionConstants::SITE_URL);
            
            if ($optionSiteUrl !== FALSE) {
                $siteUrl = $optionSiteUrl->getOptionValue();
            }
        }
        
        if (empty($siteUrl)) {
            if (empty($router)) {
                $router = new Router();
            }
            
            $urlGet  = $router->getRequest()
                              ->getUrlGet();
            $siteUrl = Util::getUrl($urlGet);
        }
        
        return $siteUrl;
    }
    
    /**
     * @param string $name
     *
     * @return bool|Option
     */
    public function searchByName($name) {
        $result = parent::searchAllByColumn($name, self::OPTION_NAME, \PDO::PARAM_STR, ['ORDER BY ' . self::COLUMN_ID . ' DESC']);
        
        return Arrays::findFirst($result);
    }
    
    /**
     * @param Option $option
     *
     * @return bool
     */
    public function updateByColumnName($option) {
        return parent::updateByColumn($option, self::OPTION_NAME);
    }
    
    public function language() {
        $language = NULL;
        
        if ($this->getConnection() != NULL) {
            $optionsManager = new OptionsManager($this->getConnection());
            $optionLanguage = $optionsManager->searchByName(OptionConstants::LANGUAGE);
            
            if (!empty($optionLanguage)) {
                $language = $optionLanguage->getOptionValue();
            }
        }
        
        Language::load($language);
    }
    
    /**
     * @param Option $object
     */
    protected function prepareStatement($object) {
        parent::addPrepareStatement(self::COLUMN_ID, $object->getId(), \PDO::PARAM_INT);
        parent::addPrepareStatement(self::OPTION_VALUE, $object->getOptionValue(), \PDO::PARAM_STR);
        parent::addPrepareStatement(self::OPTION_NAME, $object->getOptionName(), \PDO::PARAM_STR);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObject($result) {
        $option = new Option();
        $option->setId(Arrays::get($result, self::COLUMN_ID));
        $option->setOptionName(Arrays::get($result, self::OPTION_NAME));
        $option->setOptionValue(Arrays::get($result, self::OPTION_VALUE));
        
        return $option;
    }
    
}
