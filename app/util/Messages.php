<?php
/**
 * Messages.php
 */

namespace SoftnCMS\util;

use SoftnCMS\controllers\ViewController;

/**
 * Class Messages
 * @author Nicolás Marulanda P.
 */
class Messages {
    
    const TYPE_DANGER  = 'danger';
    
    const TYPE_SUCCESS = 'success';
    
    public static function sendMessagesView($message, $typeMessage) {
        ViewController::sendViewData('messages', $message);
        ViewController::sendViewData('typeMessage', $typeMessage);
    }
    
    public static function getMessages() {
        return ViewController::getViewData('messages');
    }
    
    public static function getTypeMessage() {
        return ViewController::getViewData('typeMessage');
    }
}
