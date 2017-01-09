<?php

/**
 * Modulo modelo: Gestiona los datos de un comentario para la plantilla de la aplicación.
 */
namespace SoftnCMS\models\theme;

use SoftnCMS\models\admin\Comment;

/**
 * Clase CommentTemplate para gestionar los datos de una categoría para la plantilla de la aplicación.
 * @author Nicolás Marulanda P.
 */
class CommentTemplate extends Comment {
    
    /**
     * Constructor.
     *
     * @param int $id
     */
    public function __construct($id) {
        $select = self::selectBy(self::getTableName(), $id, self::ID, \PDO::PARAM_INT);
        parent::__construct($select[0]);
    }
    
    /**
     * Método que obtiene el enlace a la pagina de usuario en la plantilla.
     *
     * @param bool $isEcho
     *
     * @return bool
     */
    public function getUrlCommentUser($isEcho = \TRUE) {
        return Template::getUrlUser($this->getCommentUserID(), $isEcho);
    }
    
    /**
     * Método que indica si el comentario fue realizado por un usuario registrado en la aplicación.
     * @return bool Si es TRUE, es un usuario registrado.
     */
    public function isRegisteredUser() {
        return !empty($this->getCommentUserID());
    }
    
}
