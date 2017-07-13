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

/**
 * Class OptionsManager
 * @author Nicolás Marulanda P.
 */
class OptionsManager extends CRUDManagerAbstract {
    
    const TABLE        = 'options';
    
    const OPTION_NAME  = 'option_name';
    
    const OPTION_VALUE = 'option_value';
    
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
            
            $host       = $_SERVER['HTTP_HOST'];// localhost
            $scheme     = $_SERVER['REQUEST_SCHEME'];// http
            $uriCurrent = $_SERVER['REQUEST_URI'];// /softn-cms/install
            $uri        = $uriCurrent;
            $url        = $scheme . '://' . $host;
            $urlGet     = $router->getRequest()
                                 ->getUrlGet();
            
            if (!empty($urlGet)) {
                $strPos = strpos($uriCurrent, $urlGet);
                //Para obtener la uri raíz de la pagina.
                $uri = substr($uriCurrent, 0, $strPos);// /softn-cms/
            }
            
            $siteUrl = Sanitize::url($url . $uri);
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
