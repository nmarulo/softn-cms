<?php
/**
 * ThemeControllerAbstract.php
 */

namespace SoftnCMS\controllers;

use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\util\form\builders\InputIntegerBuilder;
use SoftnCMS\util\Pagination;

/**
 * Class ThemeControllerAbstract
 * @author Nicolás Marulanda P.
 */
abstract class ThemeControllerAbstract {
    
    public function index($id) {
        $this->read($id);
        ViewController::view('index');
    }
    
    protected abstract function read($id);
    
    protected function pagination($count) {
        $optionsManager = new OptionsManager();
        $optionPaged    = $optionsManager->searchByName(OPTION_PAGED);
        $siteUrl        = $optionsManager->getSiteUrl();
        $paged          = InputIntegerBuilder::init('paged')
                                             ->setMethod($_GET)
                                             ->build();
        $pagedNow       = $paged->filter();
        $rowCount       = 0;
        
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
