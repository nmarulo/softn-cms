<?php
/**
 * OptionController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\classes\constants\Constants;
use SoftnCMS\classes\constants\OptionConstants;
use SoftnCMS\models\managers\MenusManager;
use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\models\managers\ProfilesManager;
use SoftnCMS\models\tables\Option;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\controller\ControllerAbstract;
use SoftnCMS\util\form\builders\InputAlphabeticBuilder;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputBooleanBuilder;
use SoftnCMS\util\form\builders\InputEmailBuilder;
use SoftnCMS\util\form\builders\InputIntegerBuilder;
use SoftnCMS\util\form\builders\InputUrlBuilder;
use SoftnCMS\util\Gravatar;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Util;

/**
 * Class OptionController
 * @author Nicolás Marulanda P.
 */
class OptionController extends ControllerAbstract {
    
    public function index() {
        $optionsManager = new OptionsManager($this->getConnectionDB());
        $this->update($optionsManager);
        $profilesManager      = new ProfilesManager($this->getConnectionDB());
        $menusManager         = new MenusManager($this->getConnectionDB());
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
        $this->sendViewOptionGravatar($optionsManager);
        $this->sendDataView([
            'optionComment'        => $optionComment,
            'optionLanguage'       => $optionLanguage,
            'menuList'             => $menuList,
            'optionMenu'           => $optionMenu,
            'listThemes'           => Util::getFilesAndDirectories(THEMES),
            'optionTitle'          => $optionTitle,
            'optionDescription'    => $optionDescription,
            'optionPaged'          => $optionPaged,
            'optionSiteUrl'        => $optionSiteUrl,
            'optionTheme'          => $optionTheme,
            'optionEmailAdmin'     => $optionEmailAdmin,
            'optionDefaultProfile' => $optionDefaultProfile,
            'profilesList'         => $profilesList,
        ]);
        $this->view();
    }
    
    private function update(OptionsManager $optionsManager) {
        if ($this->checkSubmit(Constants::FORM_UPDATE)) {
            if ($this->isValidForm()) {
                $options  = $this->getForm('options');
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
            } else {
                Messages::addDanger(__('Error en los campos de las opciones.'));
            }
        }
    }
    
    private function sendViewOptionLanguage() {
        $listLanguages = Util::getFilesAndDirectories(LANGUAGES);
        $listLanguages = array_filter($listLanguages, function($language) {
            $explodeLanguage = explode('.', $language);
            $lastPosition    = count($explodeLanguage) - 1;
            
            if (Arrays::get($explodeLanguage, $lastPosition) === FALSE || Arrays::get($explodeLanguage, 0) === FALSE) {
                return FALSE;
            }
            
            //TODO:
            return Arrays::get($explodeLanguage, $lastPosition) == 'mo' && Arrays::get($explodeLanguage, 0) != 'softncms';
        });
        $listLanguages = array_map(function($language) {
            return Arrays::get(explode('.', $language), 0);
        }, $listLanguages);
        
        $this->sendDataView(['listLanguages' => $listLanguages]);
    }
    
    private function sendViewOptionGravatar(OptionsManager $optionsManager) {
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
        $this->sendDataView([
            'gravatarSizeList'         => $sizeList,
            'gravatarDefaultImageList' => $defaultImageList,
            'gravatarRatingList'       => $ratingList,
            'gravatar'                 => $gravatar,
        
        ]);
    }
    
    private function getDataGravatar($string) {
        return Arrays::get(explode('=', $string), 1);
    }
    
    protected function formToObject() {
        $inputKeys = array_keys($this->inputs);
        $options   = array_map(function($key, $value) {
            $option = new Option();
            $option->setOptionName($key);
            $option->setOptionValue($value);
            
            return $option;
        }, $inputKeys, $this->inputs);
        $options[] = $this->formGravatar();
        
        return ['options' => $options];
    }
    
    private function formGravatar() {
        $gravatar = new Gravatar();
        $gravatar->setSize($this->getInput(OptionConstants::GRAVATAR_SIZE));
        $gravatar->setForceDefault($this->getInput(OptionConstants::GRAVATAR_FORCE_DEFAULT));
        $gravatar->setDefaultImage($this->getInput(OptionConstants::GRAVATAR_DEFAULT_IMAGE));
        $gravatar->setRating($this->getInput(OptionConstants::GRAVATAR_RATING));
        $gravatarOption = new Option();
        $gravatarOption->setOptionName(OptionConstants::GRAVATAR);
        $gravatarOption->setOptionValue(serialize($gravatar));
        
        return $gravatarOption;
    }
    
    protected function formInputsBuilders() {
        return [
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
        ];
    }
    
}
