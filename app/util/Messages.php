<?php
/**
 * Messages.php
 */

namespace SoftnCMS\util;

/**
 * Class Messages
 * @author Nicolás Marulanda P.
 */
class Messages {
    
    const TYPE_DANGER  = 'danger';
    
    const TYPE_SUCCESS = 'success';
    
    const TYPE_WARNING = 'warning';
    
    private static $messages = [];
    
    public static function addMessage($message, $typeMessage) {
        self::$messages[] = self::add($message, $typeMessage);
    }
    
    private static function add($message, $typeMessage){
        return [
            'message'     => $message,
            'typeMessage' => $typeMessage,
        ];
    }
    
    public static function getMessages() {
        $sessionMessages = self::getSessionMessages();
        $messages       = array_merge($sessionMessages, self::$messages);
        self::$messages = [];
        
        return $messages;
    }
    
    public static function getMessage($value) {
        return Arrays::get($value, 'message');
    }
    
    public static function getTypeMessage($value) {
        return Arrays::get($value, 'typeMessage');
    }
    
    public static function addSessionMessage($message, $typeMessage) {
        if (!isset($_SESSION[SESSION_MESSAGES])) {
            $_SESSION[SESSION_MESSAGES] = [];
        }
        
        $_SESSION[SESSION_MESSAGES][] = self::add($message, $typeMessage);
    }
    
    private static function getSessionMessages(){
        $messages = [];
    
        if (isset($_SESSION[SESSION_MESSAGES])) {
            $messages = $_SESSION[SESSION_MESSAGES];
            unset($_SESSION[SESSION_MESSAGES]);
        }
        
        return $messages;
    }
}
