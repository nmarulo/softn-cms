<?php
/**
 * Language.php
 */

namespace SoftnCMS\util;

use Gettext\Translations;
use Gettext\Translator;
use SoftnCMS\classes\constants\OptionConstants;
use SoftnCMS\util\form\builders\InputAlphabeticBuilder;

/**
 * Class Language
 * @author Nicolás Marulanda P.
 */
class Language {
    
    public static function load($language) {
        $paramLang  = '';
        $translator = new Translator();
        $translator->register();
        
        if (Arrays::keyExists($_GET, PARAM_LANGUAGE)) {
            $paramLang = InputAlphabeticBuilder::init(PARAM_LANGUAGE)
                                               ->setMethod($_GET)
                                               ->setSpecialChar(TRUE)
                                               ->build()
                                               ->filter();
        }
        
        if (empty($language) && empty($paramLang)) {
            $language = self::getDefaultLan();
        } elseif (empty($language)) {
            $language = $paramLang;
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
