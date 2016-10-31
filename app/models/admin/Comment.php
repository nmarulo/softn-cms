<?php

/**
 * Modulo modelo: Gestiona los datos de cada comentario.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\Escape;
use SoftnCMS\models\admin\base\Model;
use SoftnCMS\models\admin\template\CommentTemplate;

/**
 * Clase Comment para gestionar los datos de cada comentario.
 * @author Nicolás Marulanda P.
 */
class Comment extends Model {
    
    use CommentTemplate;
    
    /** Estado del comentario. 0 = Sin aprobar, 1 = Aprobado */
    const COMMENT_STATUS = 'comment_status';
    
    /** Nombre del autor. */
    const COMMENT_AUTHOR = 'comment_autor';
    
    /** Email del autor. */
    const COMMENT_AUTHOR_EMAIL = 'comment_author_email';
    
    /** Fecha de publicación del comentario. */
    const COMMENT_DATE = 'comment_date';
    
    /** Contenido del comentario. */
    const COMMENT_CONTENTS = 'comment_contents';
    
    /** Identificador del autor. 0 = para usuarios no registrados. */
    const COMMENT_USER_ID = 'comment_user_ID';
    
    /** Identificador de la entrada/post. */
    const POST_ID = 'post_ID';
    
    /** @var string Nombre de la table. */
    private static $TABLE = \DB_PREFIX . 'comments';
    
    /** @var array Datos del post. */
    private $comment;
    
    /**
     * Constructor.
     *
     * @param array $data
     */
    public function __construct($data) {
        $this->comment  = $data;
        $this->instance = $this;
    }
    
    /**
     * Método que obtiene el nombre de la tabla.
     * @return string
     */
    public static function getTableName() {
        return self::$TABLE;
    }
    
    /**
     *  Método que retorna una instancia por defecto.
     * @return Comment
     */
    public static function defaultInstance() {
        $data = [
            self::ID                   => 0,
            self::COMMENT_AUTHOR       => '',
            self::COMMENT_AUTHOR_EMAIL => '',
            self::COMMENT_CONTENTS     => '',
            self::COMMENT_STATUS       => 1,
            self::COMMENT_DATE         => '',
            self::POST_ID              => 0,
        ];
        
        return new Comment($data);
    }
    
    /**
     * Método que obtiene un comentario según su "ID".
     *
     * @param int $value
     *
     * @return Comment|bool
     */
    public static function selectByID($value) {
        $select = self::selectBy(self::$TABLE, $value, self::ID, \PDO::PARAM_INT);
        
        return self::getInstanceData($select);
    }
    
    /**
     * Método que recibe un lista de datos y retorna un instancia.
     *
     * @param array $data Lista de datos.
     *
     * @return Comment|bool Si es FALSE, no hay datos.
     */
    public static function getInstanceData($data) {
        return parent::getInstance($data, __CLASS__);
    }
    
    /**
     * Método que obtiene el identificador del comentario.
     * @return int
     */
    public function getID() {
        return $this->comment[self::ID];
    }
    
    /**
     * Método que obtiene el estado del comentario. 0 = Sin aprobar, 1 = Aprobado
     * @return int
     */
    public function getCommentStatus() {
        return $this->comment[self::COMMENT_STATUS];
    }
    
    /**
     * Método que obtiene el nombre del autor.
     * @return string
     */
    public function getCommentAuthor() {
        return $this->comment[self::COMMENT_AUTHOR];
    }
    
    /**
     * Método que obtiene el Email del autor.
     * @return string
     */
    public function getCommentAuthorEmail() {
        return $this->comment[self::COMMENT_AUTHOR_EMAIL];
    }
    
    /**
     * Método que obtiene la fecha de publicación del comentario.
     * @return string
     */
    public function getCommentDate() {
        return $this->comment[self::COMMENT_DATE];
    }
    
    /**
     * Método que obtiene el contenido del comentario.
     * @return string
     */
    public function getCommentContents() {
        return Escape::htmlDecode($this->comment[self::COMMENT_CONTENTS]);
    }
    
    /**
     * Método que obtiene el ID del autor del comentario. 0 = para usuarios no registrados.
     * @return int
     */
    public function getCommentUserID() {
        return $this->comment[self::COMMENT_USER_ID];
    }
    
    /**
     * Método que obtiene el ID de la entrada/post.
     * @return int
     */
    public function getPostID() {
        return $this->comment[self::POST_ID];
    }
    
    /**
     * Método que establece el contenido del comentario.
     *
     * @param string $value
     */
    public function setCommentContents($value) {
        $this->comment[self::COMMENT_CONTENTS] = $value;
    }
    
}
