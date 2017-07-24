<?php
/**
 * OptionController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\ControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\models\tables\Option;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\form\builders\InputAlphabeticBuilder;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputEmailBuilder;
use SoftnCMS\util\form\builders\InputIntegerBuilder;
use SoftnCMS\util\form\builders\InputUrlBuilder;
use SoftnCMS\util\form\Form;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Util;

/**
 * Class OptionController
 * @author Nicolás Marulanda P.
 */
class OptionController extends ControllerAbstract {
    
    public function index() {
        $this->update();
        parent::index();
    }
    
    private function update() {
        if (Form::submit(CRUDManagerAbstract::FORM_UPDATE)) {
            $optionsManager = new OptionsManager();
            $form           = $this->form();
            
            if (empty($form)) {
                Messages::addDanger('Error en los campos de las opciones.');
            } else {
                $options  = Arrays::get($form, 'options');
                $numError = 0;
                
                array_walk($options, function(Option $option) use ($optionsManager, &$numError) {
                    if (!$optionsManager->update($option)) {
                        ++$numError;
                    }
                });
                
                if ($numError === 0) {
                    Messages::addSuccess('Actualizado correctamente.');
                } else {
                    Messages::addDanger('Error al actualizar.');
                }
            }
        }
    }
    
    protected function form() {
        $inputs = $this->filterInputs();
        
        if (empty($inputs)) {
            return FALSE;
        }
        
        $options = array_map(function($key, $value) {
            $option = new Option();
            $option->setOptionName($key);
            $option->setOptionValue($value);
            
            return $option;
        }, array_keys($inputs), $inputs);
        
        return ['options' => $options];
    }
    
    protected function filterInputs() {
        Form::setINPUT([
            InputAlphabeticBuilder::init(OPTION_TITLE)
                                  ->build(),
            InputAlphabeticBuilder::init(OPTION_DESCRIPTION)
                                  ->setRequire(FALSE)
                                  ->build(),
            InputEmailBuilder::init(OPTION_EMAIL_ADMIN)
                             ->build(),
            InputUrlBuilder::init(OPTION_SITE_URL)
                           ->build(),
            InputIntegerBuilder::init(OPTION_PAGED)
                               ->build(),
            InputAlphanumericBuilder::init(OPTION_THEME)
                                    ->build(),
            //            InputAlphanumericBuilder::init('optionMenu')
            //                                    ->setRequire(FALSE)
            //                                    ->build(),
        ]);
        
        return Form::inputFilter();
    }
    
    protected function read() {
        $optionsManager    = new OptionsManager();
        $optionTitle       = $optionsManager->searchByName(OPTION_TITLE);
        $optionDescription = $optionsManager->searchByName(OPTION_DESCRIPTION);
        $optionPaged       = $optionsManager->searchByName(OPTION_PAGED);
        $optionSiteUrl     = $optionsManager->searchByName(OPTION_SITE_URL);
        $optionTheme       = $optionsManager->searchByName(OPTION_THEME);
        //        $optionMenu        = $optionsManager->searchByName(OPTION_);
        $optionEmailAdmin = $optionsManager->searchByName(OPTION_EMAIL_ADMIN);
        
        ViewController::sendViewData('listThemes', Util::getFilesAndDirectories(THEMES));
        ViewController::sendViewData('optionTitle', $optionTitle);
        ViewController::sendViewData('optionDescription', $optionDescription);
        ViewController::sendViewData('optionPaged', $optionPaged);
        ViewController::sendViewData('optionSiteUrl', $optionSiteUrl);
        ViewController::sendViewData('optionTheme', $optionTheme);
        ViewController::sendViewData('optionEmailAdmin', $optionEmailAdmin);
    }
    
}
