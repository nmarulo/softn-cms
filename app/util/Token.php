<?php
/**
 * Modulo: Token. Implementa la librería "Firebase\JWT".
 */

namespace SoftnCMS\util;

use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use SoftnCMS\models\managers\LoginManager;

/**
 * Clase Token para autentificar formularios.
 * @author Nicolás Marulanda P.
 */
class Token {
    
    const TOKEN_NAME = 'token_jwt';
    
    /**
     * @var bool|string Datos.
     */
    private static $TOKEN = FALSE;
    
    /**
     * Método que comprueba el token.
     * @return bool
     */
    public static function check() {
        $token = Arrays::get($_POST, self::TOKEN_NAME);
        $token = empty($token) ? Arrays::get($_GET, self::TOKEN_NAME) : $token;
        
        if (empty($token)) {
            Messages::addDanger(__('Error. Token no encontrado.'));
            
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
        $output = FALSE;
        
        try {
            $tokenDecode = (array)JWT::decode($token, TOKEN_KEY, ['HS256']);
            $data        = $tokenDecode['aud'];
            
            if ($data == self::aud()) {
                $output = TRUE;
            } else {
                Messages::addDanger(__('Error. El Token es invalido.'));
            }
            
        } catch (ExpiredException $expiredException) {
            Messages::addDanger(__('Error. El Token a caducado.'));
        } catch (SignatureInvalidException $invalidException) {
            Messages::addDanger(__('Error. El Token invalido.'));
        } catch (BeforeValidException $beforeValidException) {
            Messages::addDanger(__('Error. El Token no se puede usar.'));
        }catch (\DomainException $domainException){
            Messages::addDanger(__('Error en el formato del Token.'));
        }catch (\Exception $exception){
            Messages::addDanger(__('Error desconocido en el Token.'));
        }
        
        self::regenerate();
        
        return $output;
    }
    
    private static function aud() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $aud = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $aud = $_SERVER['REMOTE_ADDR'];
        }
        
        $aud .= @$_SERVER['HTTP_USER_AGENT'];
        $aud .= gethostname();
        
        return sha1($aud);
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
                'aud'  => self::aud(),
                'data' => [
                    'user' => LoginManager::getSession(),
                ],
            ];
            
            self::$TOKEN = JWT::encode($token, TOKEN_KEY, 'HS256');
        }
    }
    
    /**
     * Método que obtiene el campo "input" con el TOKEN para agregar al formulario.
     */
    public static function formField() {
        echo sprintf('<input type="hidden" name="%1$s" value="%2$s">', self::TOKEN_NAME, self::$TOKEN);
    }
    
    /**
     * Método que obtiene el token por url.
     *
     * @param string $concat    [Opcional]
     * @param string $separator [Opcional]
     *
     * @return string
     */
    public static function urlParameters($concat = '?', $separator = '=') {
        return sprintf('%1$s%2$s$3$s%4$s', $concat, self::TOKEN_NAME, $separator, self::$TOKEN);
    }
    
}
