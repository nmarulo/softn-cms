<?php
/**
 * OptionsManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\tables\Option;
use SoftnCMS\rute\Router;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\Sanitize;
use SoftnCMS\util\Util;

/**
 * Class OptionsManager
 * @author Nicolás Marulanda P.
 */
class OptionsManager extends CRUDManagerAbstract {
    
    const TABLE                         = 'options';
    
    const OPTION_NAME                   = 'option_name';
    
    const OPTION_VALUE                  = 'option_value';
    
    const OPTION_GRAVATAR_SIZE          = 'option_gravatar_size';
    
    const OPTION_GRAVATAR_RATING        = 'option_gravatar_rating';
    
    const OPTION_GRAVATAR_DEFAULT_IMAGE = 'option_gravatar_default_image';
    
    const OPTION_GRAVATAR_FORCE_DEFAULT = 'option_gravatar_force_default';
    
    public function getSiteUrl($router = NULL) {
        $siteUrl = '';
        
        if (!defined('INSTALL')) {
            $optionSiteUrl = $this->searchByName(OPTION_SITE_URL);
            
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
        parent::parameterQuery(self::OPTION_NAME, $name, \PDO::PARAM_STR);
        
        return parent::searchBy(self::OPTION_NAME);
    }
    
    /**
     * @param Option $option
     *
     * @return bool
     */
    public function update($option) {
        //No es necesario agregar el "parameterQuery".
        //El "OPTION_NAME" se agregara al llamar a "addParameterQuery".
        
        return parent::updateData($option, self::OPTION_NAME);
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
