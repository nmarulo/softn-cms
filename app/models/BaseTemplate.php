<?php
/**
 * BaseTemplate.php
 */

namespace SoftnCMS\models;

use SoftnCMS\controllers\Router;
use SoftnCMS\controllers\Token;
use SoftnCMS\models\admin\Option;
use SoftnCMS\models\admin\template\Template;
use SoftnCMS\models\admin\User;

/**
 * Class BaseTemplate
 * @author Nicolás Marulanda P.
 */
class BaseTemplate {
    
    private static $TITLE = FALSE;
    
    /**
     * Método que obtiene la url del "panel de administración".
     *
     * @param string $concat
     * @param bool   $isEcho [Opcional] Si es TRUE, se imprime el contenido.
     *
     * @return bool
     */
    public static function getUrlAdmin($concat = '', $isEcho = TRUE) {
        return self::getUrl(Router::getRoutes()['admin'] . "/$concat", $isEcho);
    }
    
    public static function getUrl($concat = '', $isEcho = TRUE) {
        return self::get(Router::getDATA()[SITE_URL] . $concat, $isEcho);
    }
    
    public static function get($value, $isEcho = TRUE) {
        if (!$isEcho) {
            return $value;
        }
        
        echo $value;
        
        return TRUE;
    }
    
    /**
     * Método que obtiene la url de "cierre de sesión".
     *
     * @param string $concat
     * @param bool   $isEcho
     *
     * @return bool
     */
    public static function getUrlLogout($concat = '', $isEcho = TRUE) {
        return self::getUrl(Router::getRoutes()['logout'] . '/' . $concat, $isEcho);
    }
    
    /**
     * Método que obtiene el titulo de la aplicación.
     *
     * @param string $concat
     * @param bool   $isEcho
     *
     * @return bool
     */
    public static function getTitle($concat = '', $isEcho = TRUE) {
        if (self::$TITLE === FALSE) {
            self::$TITLE = self::getSiteTitle(FALSE);
        }
        
        return self::get(self::$TITLE . $concat, $isEcho);
    }
    
    public static function setTitle($concat) {
        self::$TITLE = self::getSiteTitle(FALSE) . $concat;
    }
    
    public static function getSiteTitle($isEcho = TRUE) {
        return self::get(Option::selectByName('optionTitle')
                               ->getOptionValue(), $isEcho);
    }
    
    public static function getUrlUserUpdateSession($concat = '', $isEcho = TRUE) {
        //Nota: cambiar cuando este el Template de user.
        return Template::getUrlUser('update/' . Login::getSession() . "/$concat", $isEcho);
    }
    
    public static function getUserName($isEcho = TRUE) {
        if (self::isLogin()) {
            return self::get(self::getInstanceUser()
                                 ->getUserName(), $isEcho);
        }
        
        return 'sin sesión';
    }
    
    /**
     * Método comprueba si existe una sesión activa.
     * @return bool
     */
    public static function isLogin() {
        return Login::isLogin();
    }
    
    /**
     * @return bool|User Si es False, el usuario no existe.
     */
    public static function getInstanceUser() {
        return User::selectByID(Login::getSession());
    }
    
    public static function getTokenForm($isEcho = TRUE) {
        return self::get(Token::formField(), $isEcho);
    }
    
    public static function getTokenUrl($isEcho = TRUE) {
        return self::get(Token::urlField(), $isEcho);
    }
    
    public static function getUserLoginID(){
        return Login::getSession();
    }
    
}
