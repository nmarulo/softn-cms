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
    
    private static $messages = [];
    
    public static function addMessage($message, $typeMessage) {
        self::$messages[] = [
            'message'     => $message,
            'typeMessage' => $typeMessage,
        ];
    }
    
    public static function getMessages() {
        $messages       = self::$messages;
        self::$messages = [];
        
        return $messages;
    }
    
    public static function getMessage($value) {
        return Arrays::get($value, 'message');
    }
    
    public static function getTypeMessage($value) {
        return Arrays::get($value, 'typeMessage');
    }
}
