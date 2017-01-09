<?php
/**
 * Modulo: Gestiona los datos enviados a la plantilla del panel de administración.
 */
namespace SoftnCMS\models\admin\template;

use SoftnCMS\models\BaseTemplate;

/**
 * Clase Template para gestiona los datos enviados a la plantilla del panel de administración.
 * @author Nicolás Marulanda P.
 */
class Template extends BaseTemplate {
    
    /**
     * Método que obtiene la url de la pagina de opciones.
     *
     * @param string $concat
     * @param bool   $isEcho
     *
     * @return string
     */
    public static function getUrlOption($concat = '', $isEcho = TRUE) {
        return self::getUrlAdmin("option/$concat", $isEcho);
    }
    
    /**
     * Método que obtiene la url de la pagina de insertar post.
     *
     * @param string $concat
     * @param bool   $isEcho
     *
     * @return string
     */
    public static function getUrlPostInsert($concat = '', $isEcho = TRUE) {
        return self::getUrlPost("insert/$concat", $isEcho);
    }
    
    /**
     * Método que obtiene la url de la pagina de posts.
     *
     * @param string $concat
     * @param bool   $isEcho
     *
     * @return string
     */
    public static function getUrlPost($concat = '', $isEcho = TRUE) {
        return self::getUrlAdmin("post/$concat", $isEcho);
    }
    
    /**
     * Método que obtiene la url de la pagina de insertar categoría.
     *
     * @param string $concat
     * @param bool   $isEcho
     *
     * @return string
     */
    public static function getUrlCategoryInsert($concat = '', $isEcho = TRUE) {
        return self::getUrlCategory("insert/$concat", $isEcho);
    }
    
    /**
     * Método que obtiene la url de la pagina de categorías.
     *
     * @param string $concat
     * @param bool   $isEcho
     *
     * @return string
     */
    public static function getUrlCategory($concat = '', $isEcho = TRUE) {
        return self::getUrlAdmin("category/$concat", $isEcho);
    }
    
    /**
     * Método que obtiene la url de la pagina de comentarios.
     *
     * @param string $concat
     * @param bool   $isEcho
     *
     * @return string
     */
    public static function getUrlComment($concat = '', $isEcho = TRUE) {
        return self::getUrlAdmin("comment/$concat", $isEcho);
    }
    
    /**
     * Método que obtiene la url de la pagina de insertar etiqueta.
     *
     * @param string $concat
     * @param bool   $isEcho
     *
     * @return string
     */
    public static function getUrlTermInsert($concat = '', $isEcho = TRUE) {
        return self::getUrlTerm("insert/$concat", $isEcho);
    }
    
    /**
     * Método que obtiene la url de la pagina de etiquetas.
     *
     * @param string $concat
     * @param bool   $isEcho
     *
     * @return string
     */
    public static function getUrlTerm($concat = '', $isEcho = TRUE) {
        return self::getUrlAdmin("term/$concat", $isEcho);
    }
    
    /**
     * Método que obtiene la url de la pagina de insertar usuario.
     *
     * @param string $concat
     * @param bool   $isEcho
     *
     * @return bool
     */
    public static function getUrlUserInsert($concat = '', $isEcho = TRUE) {
        return self::getUrlUser("insert/$concat", $isEcho);
    }
    
    /**
     * Método que obtiene la url de la pagina de usuarios.
     *
     * @param string $concat
     * @param bool   $isEcho
     *
     * @return bool
     */
    public static function getUrlUser($concat = '', $isEcho = TRUE) {
        return self::getUrlAdmin("user/$concat", $isEcho);
    }
    
}
