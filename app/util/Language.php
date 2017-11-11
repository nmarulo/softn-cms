<?php
/**
 * Language.php
 */

namespace SoftnCMS\util;

use Gettext\Translations;
use Gettext\Translator;
use SoftnCMS\classes\constants\OptionConstants;
use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\util\form\builders\InputAlphabeticBuilder;

/**
 * Class Language
 * @author Nicolás Marulanda P.
 */
class Language {
    
    public static function load() {
        $paramLan   = InputAlphabeticBuilder::init(PARAM_LANGUAGE)
                                            ->setMethod($_GET)
                                            ->setSpecialChar(TRUE)
                                            ->build()
                                            ->filter();
        $language   = empty($paramLan) ? self::getDefaultLan() : $paramLan;
        $translator = new Translator();
        $translator->register();
        
        if (!defined('INSTALL') && empty($paramLan)) {
            $optionsManager = new OptionsManager();
            $optionLanguage = $optionsManager->searchByName(OptionConstants::LANGUAGE);
            
            if ($optionLanguage) {
                $language = $optionLanguage->getOptionValue();
            }
        }
        
        $pathMoFile = LANGUAGES . "$language.mo";
        
        if (file_exists($pathMoFile)) {
            $translator->loadTranslations(Translations::fromMoFile($pathMoFile));
        } else {
            Logger::getInstance()
                  ->debug('El fichero del idioma no existe.', [
                      'currentLang' => $language,
                      'path'        => $pathMoFile,
                  ]);
        }
    }
    
    private static function getDefaultLan() {
        if (defined('DEFAULT_LANGUAGE')) {
            return DEFAULT_LANGUAGE;
        }
        
        return '';
    }
    
}
