<?php
/**
 * LoginManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\tables\User;
use SoftnCMS\rute\Router;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\database\DBInterface;
use SoftnCMS\util\Logger;
use SoftnCMS\util\Token;
use SoftnCMS\util\Util;

/**
 * Class LoginManager
 * @author Nicolás Marulanda P.
 */
class LoginManager {
    
    const TOKEN_DATA_USER_ID = 'userId';
    
    /**
     * @param User        $user
     * @param bool        $rememberMe
     * @param DBInterface $connectionDB
     *
     * @return bool
     */
    public static function login($user, $rememberMe, DBInterface $connectionDB) {
        $usersManager = new UsersManager($connectionDB);
        $searchUser   = $usersManager->searchByLoginAndPassword($user->getUserLogin(), $user->getUserPassword());
        
        if (empty($searchUser)) {
            return FALSE;
        }
        
        $sessionToken           = Token::generateNewToken(self::dataToken($searchUser->getId()));
        $_SESSION[SESSION_USER] = $sessionToken;
        
        if ($rememberMe) {
            setcookie(COOKIE_USER_REMEMBER, $sessionToken, COOKIE_EXPIRE, '/');
        }
        
        return TRUE;
    }
    
    /**
     * @param string|int $userId
     *
     * @return array
     */
    public static function dataToken($userId = NULL) {
        if (empty($userId) && !empty(self::getSession())) {
            $userId = self::getUserId();
        }
        
        return [self::TOKEN_DATA_USER_ID => $userId];
    }
    
    /**
     * Método que obtiene el identificador del usuario en sesión.
     * @return string
     */
    public static function getSession() {
        return isset($_SESSION[SESSION_USER]) ? $_SESSION[SESSION_USER] : "";
    }
    
    /**
     * @return int
     */
    public static function getUserId() {
        $data = Token::getData(self::getSession());
        $user = Arrays::get($data, self::TOKEN_DATA_USER_ID);
        
        if (empty($user)) {
            Logger::getInstance()
                  ->error(__('No se encontró el usuario de la sesión.'), ['data' => $data]);
            
            Util::redirect(Router::getSiteURL(), 'login/logout');
        }
        
        return $user;
    }
    
    /**
     * Método que comprueba si ha iniciado sesión.
     * @return bool Si es FALSE, el usuario no tiene un sesión activa
     * ni tiene la opción de recordar sesión.
     */
    public static function isLogin() {
        if (!isset($_SESSION[SESSION_USER]) && !isset($_COOKIE[COOKIE_USER_REMEMBER])) {
            return FALSE;
        }
        
        if (!isset($_SESSION[SESSION_USER]) && isset($_COOKIE[COOKIE_USER_REMEMBER])) {
            $_SESSION[SESSION_USER] = $_COOKIE[COOKIE_USER_REMEMBER];
        }
        
        return self::checkSession();
    }
    
    /**
     * Método que comprueba si el valor de la variable de sesión corresponde
     * a un usuario valido.
     * @return bool
     */
    public static function checkSession() {
        $output = FALSE;
        
        if (!empty($session = self::getSession())) {
            $output = !empty(Arrays::get(Token::getData($session), self::TOKEN_DATA_USER_ID));
        }
        
        if (!$output) {
            unset($_SESSION[SESSION_USER]);
        }
        
        return $output;
    }
}
