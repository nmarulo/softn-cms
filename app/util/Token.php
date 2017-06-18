<?php
/**
 * Modulo: Token. Implementa la librería "Firebase\JWT".
 */

namespace SoftnCMS\util;

use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;

/**
 * Clase Token para autentificar formularios.
 * @author Nicolás Marulanda P.
 */
class Token {
    
    /**
     * @var bool|string Datos.
     */
    private static $TOKEN = FALSE;
    
    /**
     * Método que comprueba el token.
     * @return bool
     */
    public static function check() {
        $token = Arrays::get($_POST, 'token');
        $token = empty($token) ? Arrays::get($_GET, 'token') : $token;
        
        if (empty($token)) {
            Messages::addError('Error. Token no encontrado.');
            
            return FALSE;
        }
        
        return self::validate($token);
    }
    
    /**
     * Método que comprueba si el TOKEN es valido.
     *
     * @param $token
     *
     * @return bool
     */
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
    
    /**
     * Método que borrar y vuelve a generar un TOKEN.
     */
    public static function regenerate() {
        self::$TOKEN = FALSE;
        self::generate();
    }
    
    /**
     * Método que genera un TOKEN, si no existe uno previamente.
     */
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
    
    /**
     * Método que obtiene el campo "input" con el TOKEN para agregar al formulario.
     * @return string
     */
    public static function formField() {
        return '<input type="hidden" name="token" value="' . self::$TOKEN . '">';
    }
    
    /**
     * Método que obtiene el token por url.
     *
     * @param string $concat [Opcional]
     *
     * @return string
     */
    public static function urlField($concat = '?', $separator = '=') {
        return "${concat}token${separator}" . self::$TOKEN;
    }
    
}
