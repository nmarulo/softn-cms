<?php
/**
 * OptionController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\classes\constants\Constants;
use SoftnCMS\classes\constants\OptionConstants;
use SoftnCMS\controllers\ControllerAbstract;
use SoftnCMS\controllers\ViewController;
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
        if (Form::submit(Constants::FORM_UPDATE)) {
            $optionsManager = new OptionsManager();
            $form           = $this->form();
            
            if (empty($form)) {
                Messages::addDanger(__('Error en los campos de las opciones.'));
            } else {
                $options  = Arrays::get($form, 'options');
                $notError = TRUE;
                $len      = count($options);
                
                for ($i = 0; $i < $len && $notError; ++$i) {
                    $notError = $optionsManager->updateByColumnName(Arrays::get($options, $i));
                }
                
                if ($notError) {
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
        
        $inputKeys = array_keys($inputs);
        $options   = array_map(function($key, $value) {
            $option = new Option();
            $option->setOptionName($key);
            $option->setOptionValue($value);
            
            return $option;
        }, $inputKeys, $inputs);
        $options[] = $this->formGravatar($inputs);
        
        return ['options' => $options];
    }
    
    protected function filterInputs() {
        Form::setInput([
            InputAlphabeticBuilder::init(OptionConstants::SITE_TITLE)
                                  ->build(),
            InputAlphabeticBuilder::init(OptionConstants::SITE_DESCRIPTION)
                                  ->setRequire(FALSE)
                                  ->build(),
            InputEmailBuilder::init(OptionConstants::EMAIL_ADMIN)
                             ->build(),
            InputUrlBuilder::init(OptionConstants::SITE_URL)
                           ->build(),
            InputIntegerBuilder::init(OptionConstants::PAGED)
                               ->build(),
            InputAlphanumericBuilder::init(OptionConstants::THEME)
                                    ->build(),
            InputIntegerBuilder::init(OptionConstants::MENU)
                               ->build(),
            InputAlphabeticBuilder::init(OptionConstants::LANGUAGE)
                                  ->setAccents(FALSE)
                                  ->setSpecialChar(TRUE)
                                  ->build(),
            InputIntegerBuilder::init(OptionConstants::DEFAULT_PROFILE)
                               ->build(),
            InputIntegerBuilder::init(OptionConstants::GRAVATAR_SIZE)
                               ->build(),
            InputAlphabeticBuilder::init(OptionConstants::GRAVATAR_RATING)
                                  ->build(),
            InputAlphabeticBuilder::init(OptionConstants::GRAVATAR_DEFAULT_IMAGE)
                                  ->build(),
            InputBooleanBuilder::init(OptionConstants::GRAVATAR_FORCE_DEFAULT)
                               ->build(),
            InputBooleanBuilder::init(OptionConstants::COMMENT)
                               ->build(),
        ]);
        
        return Form::inputFilter();
    }
    
    private function formGravatar($inputs) {
        $gravatar = new Gravatar();
        $gravatar->setSize(Arrays::get($inputs, OptionConstants::GRAVATAR_SIZE));
        $gravatar->setForceDefault(Arrays::get($inputs, OptionConstants::GRAVATAR_FORCE_DEFAULT));
        $gravatar->setDefaultImage(Arrays::get($inputs, OptionConstants::GRAVATAR_DEFAULT_IMAGE));
        $gravatar->setRating(Arrays::get($inputs, OptionConstants::GRAVATAR_RATING));
        $gravatarOption = new Option();
        $gravatarOption->setOptionName(OptionConstants::GRAVATAR);
        $gravatarOption->setOptionValue(serialize($gravatar));
        
        return $gravatarOption;
    }
    
    protected function read() {
        $profilesManager      = new ProfilesManager();
        $menusManager         = new MenusManager();
        $optionsManager       = new OptionsManager();
        $optionComment        = $optionsManager->searchByName(OptionConstants::COMMENT);
        $optionTitle          = $optionsManager->searchByName(OptionConstants::SITE_TITLE);
        $optionDescription    = $optionsManager->searchByName(OptionConstants::SITE_DESCRIPTION);
        $optionPaged          = $optionsManager->searchByName(OptionConstants::PAGED);
        $optionSiteUrl        = $optionsManager->searchByName(OptionConstants::SITE_URL);
        $optionTheme          = $optionsManager->searchByName(OptionConstants::THEME);
        $optionMenu           = $optionsManager->searchByName(OptionConstants::MENU);
        $optionEmailAdmin     = $optionsManager->searchByName(OptionConstants::EMAIL_ADMIN);
        $optionLanguage       = $optionsManager->searchByName(OptionConstants::LANGUAGE);
        $optionDefaultProfile = $optionsManager->searchByName(OptionConstants::DEFAULT_PROFILE);
        $profilesList         = $profilesManager->searchAll();
        $menuList             = $menusManager->searchAllParent();
        
        $this->sendViewOptionLanguage();
        $this->sendViewOptionGravatar();
        ViewController::sendViewData('optionComment', $optionComment);
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
    
    private function sendViewOptionLanguage() {
        $listLanguages = Util::getFilesAndDirectories(LANGUAGES);
        $listLanguages = array_filter($listLanguages, function($language) {
            $aux          = explode('.', $language);
            $lastPosition = count($aux) - 1;
            
            if (Arrays::get($aux, $lastPosition) === FALSE || Arrays::get($aux, 0) === FALSE) {
                return FALSE;
            }
            
            return Arrays::get($aux, $lastPosition) == 'mo' && Arrays::get($aux, 0) != 'softncms';
        });
        $listLanguages = array_map(function($language) {
            return Arrays::get(explode('.', $language), 0);
        }, $listLanguages);
        
        ViewController::sendViewData('listLanguages', $listLanguages);
    }
    
    private function sendViewOptionGravatar() {
        $optionsManager = new OptionsManager();
        $optionGravatar = $optionsManager->searchByName(OptionConstants::GRAVATAR);
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
