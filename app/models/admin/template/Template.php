<?php
/**
 * Template.php
 */

namespace SoftnCMS\models\admin\template;

use SoftnCMS\controllers\Messages;
use SoftnCMS\controllers\Pagination;
use SoftnCMS\models\BaseTemplate;

/**
 * Class Template del panel de Administración.
 * @author Nicolás Marulanda P.
 */
class Template extends BaseTemplate {
    
    public static function getUrlOption($concat = '', $isEcho = TRUE) {
        return self::getUrlAdmin("option/$concat", $isEcho);
    }
    
    public static function getUrlPostInsert($concat = '', $isEcho = TRUE) {
        return self::getUrlPost("insert/$concat", $isEcho);
    }
    
    public static function getUrlPost($concat = '', $isEcho = TRUE) {
        return self::getUrlAdmin("post/$concat", $isEcho);
    }
    
    public static function getUrlCategoryInsert($concat = '', $isEcho = TRUE) {
        return self::getUrlCategory("insert/$concat", $isEcho);
    }
    
    public static function getUrlCategory($concat = '', $isEcho = TRUE) {
        return self::getUrlAdmin("category/$concat", $isEcho);
    }
    
    public static function getUrlComment($concat = '', $isEcho = TRUE) {
        return self::getUrlAdmin("comment/$concat", $isEcho);
    }
    
    public static function getUrlTermInsert($concat = '', $isEcho = TRUE) {
        return self::getUrlTerm("insert/$concat", $isEcho);
    }
    
    public static function getUrlTerm($concat = '', $isEcho = TRUE) {
        return self::getUrlAdmin("term/$concat", $isEcho);
    }
    
    public static function getUrlUserInsert($concat = '', $isEcho = TRUE) {
        return self::getUrlUser("insert/$concat", $isEcho);
    }
    
    /**
     * Método que obtiene la url de la pagina de usuarios del panel de administración.
     * @param string $concat
     * @param bool   $isEcho
     *
     * @return bool
     */
    public static function getUrlUser($concat = '', $isEcho = TRUE) {
        return self::getUrlAdmin("user/$concat", $isEcho);
    }
    
}
