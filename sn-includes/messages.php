<?php

/**
 * Gestión de mensajes.
 * @package sn-includes/
 */

/**
 * Mensajes de la aplicación.
 * Almacena todos los mensajes que muestra la aplicación.
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

    /** @var array Listado de mensajes.  */
    private static $messages = [];
    private static $remove = true;

    /**
     * Metodo que agrega un mensaje a la la lista de mensajes a mostrar.
     * Por defecto siempre guarda el mensaje en sesión.
     * @param string $message Mensaje a mostrar.
     * @param string $type Tipo de mensaje. Usar Messages::TYPE_*
     * @param bool $session Si es FALSE, el mensaje no se guardara en sesión.
     */
    public static function add($message, $type, $session = true, $remove = true) {
        $data = ['message' => $message, 'type' => $type];
        if ($session) {
            $_SESSION['messages'][] = $data;
        } else {
            self::$messages[] = $data;
        }
        
        self::$remove = $remove;
    }

    /**
     * Metodo que muestra los mensajes. Siempre comprueba si hay mensajes
     * guardados en sesión.
     */
    public static function show() {
        self::session();
        $scriptTime = '<script> if(timeout != undefined){clearTimeout(timeout);}  var timeout = setTimeout(function(){ $("#messages").remove()}, 5000);</script>';

        if (count(self::$messages)) {
            $str = '<div id="messages"><div id="messages-content" class="modal-dialog">';
            foreach (self::$messages as $value) {
                $str .= "<div class='alert alert-" . $value['type'] . " alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>" . $value['message'] . '</div>';
            }
            $str .= self::$remove ? $scriptTime : '';
            $str .= '</div></div>';
            
            echo $str;
            self::$messages = [];
        }
    }
    
    public static function getMessages(){
        self::session();
        $out = 0;
        if (count(self::$messages)) {
            $out = self::$messages;
            self::$messages = [];
        }
        return $out;
    }

    /**
     * Metodo que obtiene los mensajes guardados en sesión.
     */
    private static function session() {
        if (isset($_SESSION['messages'])) {
            self::$messages = $_SESSION['messages'];
            unset($_SESSION['messages']);
        }
    }

}
