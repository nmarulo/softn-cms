<?php
/**
 * OptionController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\ControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\managers\MenusManager;
use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\models\managers\ProfilesManager;
use SoftnCMS\models\tables\Option;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\form\builders\InputAlphabeticBuilder;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputBooleanBuilder;
use SoftnCMS\util\form\builders\InputEmailBuilder;
use SoftnCMS\util\form\builders\InputIntegerBuilder;
use SoftnCMS\util\form\builders\InputUrlBuilder;
use SoftnCMS\util\form\Form;
use SoftnCMS\util\Gravatar;
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
                Messages::addDanger(__('Error en los campos de las opciones.'));
            } else {
                $options  = Arrays::get($form, 'options');
                $numError = 0;
                
                array_walk($options, function(Option $option) use ($optionsManager, &$numError) {
                    if (!$optionsManager->update($option)) {
                        ++$numError;
                    }
                });
                
                if ($numError === 0) {
                    Messages::addSuccess(__('Actualizado correctamente.'));
                } else {
                    Messages::addDanger(__('Error al actualizar.'));
                }
            }
        }
    }
    
    protected function form() {
        $inputs = $this->filterInputs();
        
        if (empty($inputs)) {
            return FALSE;
        }
        
        $gravatar = new Gravatar();
        $gravatar->setSize(Arrays::get($inputs, OptionsManager::OPTION_GRAVATAR_SIZE));
        $gravatar->setForceDefault(Arrays::get($inputs, OptionsManager::OPTION_GRAVATAR_FORCE_DEFAULT));
        $gravatar->setDefaultImage(Arrays::get($inputs, OptionsManager::OPTION_GRAVATAR_DEFAULT_IMAGE));
        $gravatar->setRating(Arrays::get($inputs, OptionsManager::OPTION_GRAVATAR_RATING));
        $gravatarOption = new Option();
        $gravatarOption->setOptionName(OPTION_GRAVATAR);
        $gravatarOption->setOptionValue(serialize($gravatar));
        
        $inputKeys = array_keys($inputs);
        $options   = array_map(function($key, $value) {
            $option = new Option();
            $option->setOptionName($key);
            $option->setOptionValue($value);
            
            return $option;
        }, $inputKeys, $inputs);
        $options[] = $gravatarOption;
        
        return ['options' => $options];
    }
    
    protected function filterInputs() {
        Form::setInput([
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
            InputIntegerBuilder::init(OPTION_MENU)
                               ->build(),
            InputAlphabeticBuilder::init(OPTION_LANGUAGE)
                                  ->setAccents(FALSE)
                                  ->setSpecialChar(TRUE)
                                  ->build(),
            InputIntegerBuilder::init(OPTION_DEFAULT_PROFILE)
                               ->build(),
            InputIntegerBuilder::init(OptionsManager::OPTION_GRAVATAR_SIZE)
                               ->build(),
            InputAlphabeticBuilder::init(OptionsManager::OPTION_GRAVATAR_RATING)
                                  ->build(),
            InputAlphabeticBuilder::init(OptionsManager::OPTION_GRAVATAR_DEFAULT_IMAGE)
                                  ->build(),
            InputBooleanBuilder::init(OptionsManager::OPTION_GRAVATAR_FORCE_DEFAULT)
                               ->build(),
        ]);
        
        return Form::inputFilter();
    }
    
    protected function read() {
        $profilesManager      = new ProfilesManager();
        $menusManager         = new MenusManager();
        $optionsManager       = new OptionsManager();
        $optionTitle          = $optionsManager->searchByName(OPTION_TITLE);
        $optionDescription    = $optionsManager->searchByName(OPTION_DESCRIPTION);
        $optionPaged          = $optionsManager->searchByName(OPTION_PAGED);
        $optionSiteUrl        = $optionsManager->searchByName(OPTION_SITE_URL);
        $optionTheme          = $optionsManager->searchByName(OPTION_THEME);
        $optionMenu           = $optionsManager->searchByName(OPTION_MENU);
        $optionEmailAdmin     = $optionsManager->searchByName(OPTION_EMAIL_ADMIN);
        $optionLanguage       = $optionsManager->searchByName(OPTION_LANGUAGE);
        $optionDefaultProfile = $optionsManager->searchByName(OPTION_DEFAULT_PROFILE);
        $profilesList         = $profilesManager->read();
        $menuList             = $menusManager->searchAllParent();
        $listLanguages        = Util::getFilesAndDirectories(LANGUAGES);
        $listLanguages        = array_filter($listLanguages, function($language) {
            $aux          = explode('.', $language);
            $lastPosition = count($aux) - 1;
            
            if (Arrays::get($aux, $lastPosition) === FALSE || Arrays::get($aux, 0) === FALSE) {
                return FALSE;
            }
            
            return Arrays::get($aux, $lastPosition) == 'mo' && Arrays::get($aux, 0) != 'softncms';
        });
        $listLanguages        = array_map(function($language) {
            return Arrays::get(explode('.', $language), 0);
        }, $listLanguages);
        
        $this->gravatar();
        ViewController::sendViewData('listLanguages', $listLanguages);
        ViewController::sendViewData('optionLanguage', $optionLanguage);
        ViewController::sendViewData('menuList', $menuList);
        ViewController::sendViewData('optionMenu', $optionMenu);
        ViewController::sendViewData('listThemes', Util::getFilesAndDirectories(THEMES));
        ViewController::sendViewData('optionTitle', $optionTitle);
        ViewController::sendViewData('optionDescription', $optionDescription);
        ViewController::sendViewData('optionPaged', $optionPaged);
        ViewController::sendViewData('optionSiteUrl', $optionSiteUrl);
        ViewController::sendViewData('optionTheme', $optionTheme);
        ViewController::sendViewData('optionEmailAdmin', $optionEmailAdmin);
        ViewController::sendViewData('optionDefaultProfile', $optionDefaultProfile);
        ViewController::sendViewData('profilesList', $profilesList);
        
    }
    
    private function gravatar() {
        $optionsManager = new OptionsManager();
        $optionGravatar = $optionsManager->searchByName(OPTION_GRAVATAR);
        $gravatar       = unserialize($optionGravatar->getOptionValue());
        
        if (empty($gravatar)) {
            $gravatar = new Gravatar();
        }
        
        $gravatar->setSize($this->getDataGravatar($gravatar->getSize()), FALSE);
        $gravatar->setRating($this->getDataGravatar($gravatar->getRating()), FALSE);
        $gravatar->setDefaultImage($this->getDataGravatar($gravatar->getDefaultImage()), FALSE);
        
        $sizeList         = [
            32,
            64,
            128,
            256,
        ];
        $defaultImageList = [
            Gravatar::DEFAULT_IMAGE_MM,
            Gravatar::DEFAULT_IMAGE_BLANK,
            Gravatar::DEFAULT_IMAGE_IDENTICON,
            Gravatar::DEFAULT_IMAGE_MOSTERID,
            Gravatar::DEFAULT_IMAGE_RETRO,
            Gravatar::DEFAULT_IMAGE_WAVATAR,
        ];
        $ratingList       = [
            Gravatar::RATING_G,
            Gravatar::RATING_PG,
            Gravatar::RATING_R,
            Gravatar::RATING_X,
        ];
        ViewController::sendViewData('gravatarSizeList', $sizeList);
        ViewController::sendViewData('gravatarDefaultImageList', $defaultImageList);
        ViewController::sendViewData('gravatarRatingList', $ratingList);
        ViewController::sendViewData('gravatar', $gravatar);
    }
    
    private function getDataGravatar($string) {
        return Arrays::get(explode('=', $string), 1);
    }
    
}
