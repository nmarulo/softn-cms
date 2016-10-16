<?php
/**
 * Token.php
 */

namespace SoftnCMS\controllers;

use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use SoftnCMS\models\Login;

/**
 * Class Token
 * @author Nicolás Marulanda P.
 */
class Token {
    
    private static $TOKEN = FALSE;
    
    public static function regenerate() {
        self::$TOKEN = FALSE;
        self::generate();
    }
    
    public static function check() {
        $token        = filter_input(INPUT_POST, 'token');
        $token        = empty($token) ? filter_input(INPUT_GET, 'token') : $token;
        
        if (empty($token)) {
            Messages::addError('Error. Token invalido.');
            
            return FALSE;
        }
        
        return self::validate($token);
    }
    
    public static function generate() {
        if (empty(self::$TOKEN)) {
            $time  = time();
            $token = [
                //Hora actual en segundos
                "iat"  => $time,
                //La hora, en segundos, en que el token caduca. Puede ser como máximo 3600 segundos posterior a iat.
                'exp'  => $time + (60 * 60),
                'nbf'  => $time,
                'data' => [
                    'user' => Login::getSession(),
                ],
            ];
            
            self::$TOKEN = JWT::encode($token, TOKEN_KEY, 'HS256');
        }
    }
    
    private static function validate($token) {
        $output = TRUE;
        
        try {
            $tokenDecode = (array)JWT::decode($token, TOKEN_KEY, ['HS256']);
            $data        = (array)$tokenDecode['data'];
            
            if ($data['user'] != Login::getSession()) {
                Messages::addError('Error. El Token es invalido.');
                
                $output = FALSE;
            }
            
        } catch (ExpiredException $expiredException) {
            Messages::addError('Error. El Token a caducado.');
            $output = FALSE;
            
        } catch (SignatureInvalidException $invalidException) {
            Messages::addError('Error. El Token invalido.');
            $output = FALSE;
            
        } catch (BeforeValidException $beforeValidException) {
            Messages::addError('Error. El Token no se puede usar.');
            $output = FALSE;
        }
        
        self::regenerate();
        
        return $output;
    }
    
    public static function formField() {
        return '<input type="hidden" name="token" value="' . self::$TOKEN . '">';
    }
    
    public static function urlField($concat = '?') {
        return "${concat}token=" . self::$TOKEN;
    }
    
}
