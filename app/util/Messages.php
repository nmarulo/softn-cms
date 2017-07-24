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
    
    private static $MESSAGES = [];
    
    /**
     * @param string $message
     * @param bool   $session
     */
    public static function addDanger($message, $session = FALSE) {
        self::add($message, Messages::TYPE_DANGER, $session);
    }
    
    /**
     * @param string $message
     * @param string $type
     * @param bool   $session
     */
    private static function add($message, $type, $session = FALSE) {
        $intPut = [
            'message'     => $message,
            'typeMessage' => $type,
        ];
        
        if ($session) {
            if (!isset($_SESSION[SESSION_MESSAGES])) {
                $_SESSION[SESSION_MESSAGES] = [];
            }
            
            $_SESSION[SESSION_MESSAGES][] = $intPut;
        } else {
            self::$MESSAGES[] = $intPut;
        }
    }
    
    /**
     * @param string $message
     * @param bool   $session
     */
    public static function addSuccess($message, $session = FALSE) {
        self::add($message, Messages::TYPE_SUCCESS, $session);
    }
    
    /**
     * @param string $message
     * @param bool   $session
     */
    public static function addWarning($message, $session = FALSE) {
        self::add($message, Messages::TYPE_WARNING, $session);
    }
    
    /**
     * @return array
     */
    public static function getMessages() {
        $sessionMessages = self::getSessionMessages();
        $messages        = array_merge($sessionMessages, self::$MESSAGES);
        self::$MESSAGES  = [];
        
        return $messages;
    }
    
    /**
     * @return array
     */
    private static function getSessionMessages() {
        $messages = [];
        
        if (isset($_SESSION[SESSION_MESSAGES])) {
            $messages = $_SESSION[SESSION_MESSAGES];
            unset($_SESSION[SESSION_MESSAGES]);
        }
        
        return $messages;
    }
    
    /**
     * @param array $value
     *
     * @return bool|string
     */
    public static function getMessage($value) {
        return Arrays::get($value, 'message');
    }
    
    /**
     * @param array $value
     *
     * @return bool|string
     */
    public static function getTypeMessage($value) {
        return Arrays::get($value, 'typeMessage');
    }
}
