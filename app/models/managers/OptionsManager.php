<?php
/**
 * OptionsManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\tables\Option;
use SoftnCMS\util\Arrays;

/**
 * Class OptionsManager
 * @author Nicolás Marulanda P.
 */
class OptionsManager extends CRUDManagerAbstract {
    
    const TABLE        = 'options';
    
    const OPTION_NAME  = 'option_name';
    
    const OPTION_VALUE = 'option_value';
    
    public function getSiteUrl() {
        //TODO: temporalmente.
        $siteUrl       = "http://localhost/softn-cms/";
        $optionSiteUrl = $this->searchByName(OPTION_SITE_URL);
        
        if ($optionSiteUrl !== FALSE) {
            $siteUrl = $optionSiteUrl->getOptionValue();
        }
        
        return $siteUrl;
    }
    
    /**
     * @param string $name
     *
     * @return bool|Option
     */
    public function searchByName($name) {
        parent::parameterQuery(self::OPTION_NAME, $name, \PDO::PARAM_STR);
        
        return parent::searchBy(self::OPTION_NAME);
    }
    
    /**
     * @param Option $object
     */
    protected function addParameterQuery($object) {
        parent::parameterQuery(self::OPTION_VALUE, $object->getOptionValue(), \PDO::PARAM_STR);
        parent::parameterQuery(self::OPTION_NAME, $object->getOptionName(), \PDO::PARAM_STR);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObjectTable($result) {
        parent::buildObjectTable($result);
        $option = new Option();
        $option->setId(Arrays::get($result, self::ID));
        $option->setOptionName(Arrays::get($result, self::OPTION_NAME));
        $option->setOptionValue(Arrays::get($result, self::OPTION_VALUE));
        
        return $option;
    }
}
