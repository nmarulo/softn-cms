<?php
/**
 * BaseTemplate.php
 */

namespace SoftnCMS\models;

use SoftnCMS\controllers\Messages;
use SoftnCMS\controllers\Pagination;
use SoftnCMS\controllers\Router;
use SoftnCMS\controllers\Token;
use SoftnCMS\models\admin\Option;
use SoftnCMS\models\admin\template\Template as AdminTemplate;
use SoftnCMS\models\theme\Template as ThemeTemplate;
use SoftnCMS\models\admin\User;

/**
 * Class BaseTemplate
 * @author Nicolás Marulanda P.
 */
class BaseTemplate {
    
    private static $TITLE = FALSE;
    
    /**
     * @var bool|Pagination
     */
    private static $PAGINATION = FALSE;
    
    /**
     * Método que incluye la pagina de navegación.
     * @return mixed
     */
    public static function getPagedNav() {
        $pagedNav = self::getPagination();
        
        $path = \VIEWS_ADMIN;
        
        if (Router::getRequest()
                  ->getRoute() != Router::getRoutes()['admin']
        ) {
            $path = THEMES . DIRECTORY_SEPARATOR . ThemeTemplate::getNameTheme() . DIRECTORY_SEPARATOR;
        }
        
        return require $path . 'pagednav.php';
    }
    
    /**
     * Método que obtiene la paginación.
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
     * Método que estable la instancia de paginación.
     *
     * @param Pagination $pagination
     */
    public static function setPagination($pagination) {
        self::$PAGINATION = $pagination;
    }
    
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
        return self::getUrl(Router::getRoutes()['logout'] . "/$concat", $isEcho);
    }
    
    /**
     * Método que obtiene la url de "inicio de sesión".
     *
     * @param string $concat
     * @param bool   $isEcho
     *
     * @return bool
     */
    public static function getUrlLogin($concat = '', $isEcho = TRUE) {
        return self::getUrl(Router::getRoutes()['login'] . "/$concat", $isEcho);
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
    
    /**
     * Método que obtiene el titulo de la aplicación.
     *
     * @param bool $isEcho
     *
     * @return bool
     */
    public static function getSiteTitle($isEcho = TRUE) {
        return self::get(Option::selectByName('optionTitle')
                               ->getOptionValue(), $isEcho);
    }
    
    /**
     * Método que obtiene la url para editar el usuario de la sesión actual.
     *
     * @param string $concat
     * @param bool   $isEcho
     *
     * @return bool
     */
    public static function getUrlUserUpdateSession($concat = '', $isEcho = TRUE) {
        return self::getUrlUserUpdate(Login::getSession() . "/$concat", $isEcho);
    }
    
    public static function getUrlUserUpdate($concat = '', $isEcho = TRUE) {
        return AdminTemplate::getUrlUser("update/$concat", $isEcho);
    }
    
    /**
     * Método que obtiene el nombre de usuario de la sesión.
     *
     * @param bool $isEcho
     *
     * @return bool|string
     */
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
    
    /**
     * Método que obtiene el ID de usuario de la sesión.
     * @return int
     */
    public static function getUserLoginID() {
        return Login::getSession();
    }
    
    public static function getUrlRegister($concat = '', $isEcho = TRUE) {
        return self::getUrl(Router::getRoutes()['register'] . "/$concat", $isEcho);
    }
    
}
