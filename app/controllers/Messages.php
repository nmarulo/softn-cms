<?php

/**
 * Modulo: Mensajes emergentes.
 */

namespace SoftnCMS\controllers;

/**
 * Clase Messages para los mensajes emergentes.
 * @author Nicolás Marulanda P.
 */
class Messages {
    
    /** Tipo de mensaje: Información. */
    const TYPE_I = 'info';
    
    /** Tipo de mensaje: Error. */
    const TYPE_E = 'danger';
    
    /** Tipo de mensaje: Correcto. */
    const TYPE_S = 'success';
    
    /** Tipo de mensaje: Advertencia. */
    const TYPE_W = 'warning';
    
    /**
     * Método que obtiene los mensajes.
     * @return array
     */
    public static function getMessages() {
        return self::session();
    }
    
    /**
     * Método que obtiene los mensajes guardados en sesión.
     */
    private static function session() {
        $message = [];
        
        if (isset($_SESSION['messages'])) {
            $message = $_SESSION['messages'];
            unset($_SESSION['messages']);
        }
        
        return $message;
    }
    
    /**
     * Método que agrega un mensaje de tipo "Información".
     *
     * @param string $message
     */
    public static function addInfo($message) {
        self::add($message, self::TYPE_I);
    }
    
    /**
     * Método que agrega el mensaje a la sesión.
     *
     * @param string $message Mensaje.
     * @param string $type    Tipo de mensaje. Usar las constantes "Messages::TYPE_*".
     */
    private static function add($message, $type) {
        if (!isset($_SESSION['messages'])) {
            $_SESSION['messages'] = [];
        }
        
        $_SESSION['messages'][] = [
            'message' => $message,
            'type'    => $type,
        ];
    }
    
    /**
     * Método que agrega un mensaje de tipo "Error".
     *
     * @param string $message
     */
    public static function addError($message) {
        self::add($message, self::TYPE_E);
    }
    
    /**
     * Método que agrega un mensaje de tipo "Correcto".
     *
     * @param string $message
     */
    public static function addSuccess($message) {
        self::add($message, self::TYPE_S);
    }
    
    /**
     * Método que agrega un mensaje de tipo "Advertencia".
     *
     * @param string $message
     */
    public static function addWarning($message) {
        self::add($message, self::TYPE_W);
    }
    
}
