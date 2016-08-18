<?php

/**
 * Modulo del modelo de comentarios.
 * Gestiona el proceso de insertar comentarios.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\Comment;

/**
 * Clase que gestiona el proceso de insertar comentarios.
 *
 * @author Nicolás Marulanda P.
 */
class CommentInsert {

    /** @var string Nombre de las columnas. */
    private static $COLUMNS = Comment::COMMENT_AUTHOR . ', ' . Comment::COMMENT_AUTHOR_EMAIL . ', ' . Comment::COMMENT_DATE . ', ' . Comment::COMMENT_CONTENTS . ', ' . Comment::COMMENT_STATUS . ', ' . Comment::COMMENT_USER_ID . ', ' . Comment::POST_ID;

    /** @var string Nombre de los indices para preparar la consulta. */
    private static $VALUES = ':' . Comment::COMMENT_AUTHOR . ', :' . Comment::COMMENT_AUTHOR_EMAIL . ', :' . Comment::COMMENT_DATE . ', :' . Comment::COMMENT_CONTENTS . ', :' . Comment::COMMENT_STATUS . ', :' . Comment::COMMENT_USER_ID . ', :' . Comment::POST_ID;

    /** @var array Lista con los indices, valores y tipos de datos para la consulta. */
    private $prepareStatement;

    /** @var int Identificador del INSERT realizado. */
    private $lastInsertId;

    /** @var string Nombre del autor. */
    private $commentAutor;

    /** @var string Email del autor. */
    private $commentAuthorEmail;

    /** @var string Estado del comentario. 0 = Sin aprobar, 1 = Aprobado */
    private $commentStatus;

    /** @var string Contenido del comentario. */
    private $commentContents;

    /** @var string Identificador de la entrada/post. */
    private $postID;

    /** @var string Identificador del autor. 0 = para usuarios no registrados. */
    private $commentUserID;

    /**
     * Constructor.
     * @param string $commentAutor Nombre del autor.
     * @param string $commentAuthorEmail Email del autor.
     * @param int $commentStatus Estado del comentario. 0 = Sin aprobar, 1 = Aprobado
     * @param string $commentContents Contenido del comentario.
     * @param int $postID Identificador de la entrada/post.
     * @param int $commentUserID Identificador del autor.
     */
    public function __construct($commentAutor, $commentAuthorEmail, $commentStatus, $commentContents, $postID, $commentUserID) {
        $this->prepareStatement = [];
        $this->commentAutor = $commentAutor;
        $this->commentAuthorEmail = $commentAuthorEmail;
        $this->commentStatus = $commentStatus;
        $this->commentContents = $commentContents;
        $this->postID = $postID;
        $this->commentUserID = $commentUserID;
    }

    /**
     * Metodo que realiza el proceso de insertar el comentario en la base de datos.
     * @return bool Si es TRUE, todo se realizo correctamente.
     */
    public function insert() {
        $db = DBController::getConnection();
        $table = Comment::getTableName();
        $this->prepare();

        if ($db->insert($table, self::$COLUMNS, self::$VALUES, $this->prepareStatement)) {
            $this->lastInsertId = $db->lastInsertId();
            return \TRUE;
        }

        return \FALSE;
    }

    /**
     * Metodo que obtiene el identificador del nuevo comentario.
     * @return int
     */
    public function getLastInsertId() {
        return $this->lastInsertId;
    }

    /**
     * Metodo que establece los datos a preparar.
     */
    private function prepare() {
        $date = \date('Y-m-d H:i:s', \time());
        $this->addPrepare(':' . Comment::COMMENT_AUTHOR, $this->commentAutor, \PDO::PARAM_STR);
        $this->addPrepare(':' . Comment::COMMENT_AUTHOR_EMAIL, $this->commentAuthorEmail, \PDO::PARAM_STR);
        $this->addPrepare(':' . Comment::COMMENT_DATE, $date, \PDO::PARAM_STR);
        $this->addPrepare(':' . Comment::COMMENT_CONTENTS, $this->commentContents, \PDO::PARAM_STR);
        $this->addPrepare(':' . Comment::COMMENT_STATUS, $this->commentStatus, \PDO::PARAM_INT);
        $this->addPrepare(':' . Comment::COMMENT_USER_ID, $this->commentUserID, \PDO::PARAM_INT);
        $this->addPrepare(':' . Comment::POST_ID, $this->postID, \PDO::PARAM_INT);
    }

    /**
     * Metodo que guarda los datos establecidos.
     * @param string $parameter Indice a buscar. EJ: ":ID"
     * @param string $value Valor del indice.
     * @param int $dataType Tipo de dato. EJ: \PDO::PARAM_*
     */
    private function addPrepare($parameter, $value, $dataType) {
        $this->prepareStatement[] = DBController::prepareStatement($parameter, $value, $dataType);
    }

}
