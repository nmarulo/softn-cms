<?php
/**
 * Language.php
 */

namespace SoftnCMS\util;

use Gettext\Translations;
use Gettext\Translator;
use SoftnCMS\models\managers\OptionsManager;

/**
 * Class Language
 * @author Nicolás Marulanda P.
 */
class Language {
    
    public static function load() {
        $paramLan   = Arrays::get($_GET, PARAM_LANGUAGE);
        $language   = empty($paramLan) ? self::getDefaultLan() : $paramLan;
        $translator = new Translator();
        $translator->register();
        
        if (!defined('INSTALL') && empty($paramLan)) {
            $optionsManager = new OptionsManager();
            $optionLanguage = $optionsManager->searchByName(OPTION_LANGUAGE);
            
            if ($optionLanguage) {
                $language = $optionLanguage->getOptionValue();
            }
        }
        
        $pathMoFile = ABSPATH . "util/languages/$language.mo";
        
        if (file_exists($pathMoFile)) {
            $translator->loadTranslations(Translations::fromMoFile($pathMoFile));
        }
    }
    
    private static function getDefaultLan() {
        if (defined('DEFAULT_LANGUAGE')) {
            return DEFAULT_LANGUAGE;
        }
        
        return '';
    }
    
}
