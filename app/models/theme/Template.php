<?php

/**
 * Modulo: Gestiona los datos enviados a la plantilla de la aplicación.
 */
namespace SoftnCMS\models\theme;

use SoftnCMS\models\admin\Option;
use SoftnCMS\models\BaseTemplate;

/**
 * Clase Template para gestionar los datos enviados a la plantilla de la aplicación.
 * @author Nicolás Marulanda P.
 */
class Template extends BaseTemplate {
    
    /** @var array Lista con los datos a incluir en el HEAD de la plantilla. */
    //    private static $head;
    
    /** @var array Lista con los datos a incluir al final de la plantilla. */
    //    private static $footer;
    
    /**
     * Método que obtiene la url de la pagina de un post.
     *
     * @param string $concat Identificador y valores a concatenar en la ruta.
     * @param bool   $isEcho
     *
     * @return string
     */
    public static function getUrlPost($concat, $isEcho = TRUE) {
        return self::getUrl("post/$concat", $isEcho);
    }
    
    /**
     * Método que obtiene la url de la pagina de un usuario.
     *
     * @param string $concat Identificador y valores a concatenar en la ruta.
     * @param bool   $isEcho
     *
     * @return string
     */
    public static function getUrlUser($concat, $isEcho = TRUE) {
        return self::getUrl("user/$concat", $isEcho);
    }
    
    /**
     * Método que obtiene la url de la pagina de una categoría.
     *
     * @param string $concat Identificador y valores a concatenar en la ruta.
     * @param bool   $isEcho
     *
     * @return string
     */
    public static function getUrlCategory($concat, $isEcho = TRUE) {
        return self::getUrl("category/$concat", $isEcho);
    }
    
    /**
     * Método que obtiene la url de la pagina de una etiqueta.
     *
     * @param string $concat Identificador y valores a concatenar en la ruta.
     * @param bool   $isEcho
     *
     * @return string
     */
    public static function getUrlTerm($concat, $isEcho = TRUE) {
        return self::getUrl("term/$concat", $isEcho);
    }
    
    /**
     * Método que obtiene los estilos css y otras etiquetas a incluir
     * en el "HEAD" de la plantilla.
     *
     * @param bool $isEcho [Opcional] Si es TRUE, se imprime el contenido.
     *
     * @return array Lista con los datos a incluir en el HEAD de la plantilla.
     */
    //    public static function getHead($isEcho = \TRUE) {
    //        if (!$isEcho) {
    //
    //            return $this->head;
    //        }
    //
    //        $echo = "";
    //
    //        foreach ($this->head as $value) {
    //            $echo .= $value;
    //        }
    //
    //        echo $echo;
    //    }
    
    /**
     * Metodo que obtiene los script js y otras etiquetas a incluir
     * al final de la pagina.
     *
     * @param bool $isEcho [Opcional] Si es TRUE, se imprime el contenido.
     *
     * @return array Lista con los datos a incluir al final de la plantilla.
     */
    //    public static function getFooter($isEcho = \TRUE) {
    //        if (!$isEcho) {
    //
    //            return $this->footer;
    //        }
    //
    //        $echo = "";
    //
    //        foreach ($this->footer as $value) {
    //            $echo .= $value;
    //        }
    //
    //        echo $echo;
    //    }
    
    /**
     * Método que obtiene la url de la plantilla.
     *
     * @param bool $isEcho [Opcional] Si es TRUE, se imprime el contenido.
     *
     * @return string
     */
    public static function getUrlTheme($isEcho = \TRUE) {
        return self::getUrl(APP_THEMES . '/' . self::getNameTheme() . '/', $isEcho);
    }
    
    /**
     * Método que obtiene el nombre del tema de la aplicación.
     * @return string
     */
    public static function getNameTheme() {
        return Option::selectByName('optionTheme')
                     ->getOptionValue();
    }
    
    /**
     * Método que obtiene los elementos del menu.
     *
     * @param string $name Nombre del menu.
     *
     * @return array
     */
    public static function getMenu($name) {
        return [];
    }
    
}
