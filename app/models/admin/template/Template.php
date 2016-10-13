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
    
    /**
     * @var bool|Pagination
     */
    private static $PAGINATION = FALSE;
    
    public static function getPagedNav() {
        $pagedNav = self::getPagination();
        
        return require \VIEWS_ADMIN . 'pagednav.php';
    }
    
    /**
     * @return Pagination
     */
    public static function getPagination() {
        if (self::$PAGINATION === FALSE) {
            self::$PAGINATION = new Pagination(0, 1);
            Messages::addWarning('La paginación no ha sido establecida.');
        }
        
        return self::$PAGINATION;
    }
    
    /**
     * @param Pagination $pagination
     */
    public static function setPagination($pagination) {
        self::$PAGINATION = $pagination;
    }
    
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
    
    public static function getUrlUser($concat = '', $isEcho = TRUE) {
        return self::getUrlAdmin("user/$concat", $isEcho);
    }
    
}
