<?php
/**
 * ControllerAbstract.php
 */

namespace SoftnCMS\controllers;

use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\form\builders\InputIntegerBuilder;
use SoftnCMS\util\Pagination;

/**
 * Class ControllerAbstract
 * @author Nicolás Marulanda P.
 */
abstract class ControllerAbstract {
    
    public abstract function index();
    
    public function reloadAJAX() {
        $this->read();
        ViewController::singleView('data');
    }
    
    protected abstract function read();
    
    protected function pagination($count) {
        $optionsManager = new OptionsManager();
        $optionPaged    = $optionsManager->searchByName(OPTION_PAGED);
        $optionSiteUrl  = $optionsManager->searchByName(OPTION_SITE_URL);
        $paged          = InputIntegerBuilder::init('paged')
                                             ->build();
        $pagedNow       = $paged->filter();
        $rowCount       = 0;
        $siteUrl        = ''; //TODO: pendiente, ruta por defecto en caso de que "$optionSiteUrl" sea false
        
        if ($optionSiteUrl !== FALSE) {
            $siteUrl = $optionSiteUrl->getOptionValue();
        }
        
        if ($optionPaged !== FALSE) {
            $rowCount = $optionPaged->getOptionValue();
        }
        
        $pagination = new Pagination($pagedNow, $count, $rowCount, $siteUrl);
        
        if ($pagination->isShowPagination()) {
            ViewController::sendViewData('pagination', $pagination);
            
            return $pagination->getBeginRow() . ',' . $pagination->getRowCount();
        }
        
        return FALSE;
    }
    
}
