<?php

/**
 * Modulo del modelo de comentarios.
 * Gestiona el proceso de insertar comentarios.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\base\ModelInsert;

/**
 * Clase que gestiona el proceso de insertar comentarios.
 *
 * @author Nicolás Marulanda P.
 */
class CommentInsert extends ModelInsert {

    /** @var string Nombre de las columnas. */
    private static $COLUMNS = Comment::COMMENT_AUTHOR . ', ' . Comment::COMMENT_AUTHOR_EMAIL . ', ' . Comment::COMMENT_DATE . ', ' . Comment::COMMENT_CONTENTS . ', ' . Comment::COMMENT_STATUS . ', ' . Comment::COMMENT_USER_ID . ', ' . Comment::POST_ID;

    /** @var string Nombre de los indices para preparar la consulta. */
    private static $VALUES = ':' . Comment::COMMENT_AUTHOR . ', :' . Comment::COMMENT_AUTHOR_EMAIL . ', :' . Comment::COMMENT_DATE . ', :' . Comment::COMMENT_CONTENTS . ', :' . Comment::COMMENT_STATUS . ', :' . Comment::COMMENT_USER_ID . ', :' . Comment::POST_ID;

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
        parent::__construct(Comment::getTableName(), self::$COLUMNS, self::$VALUES);
        $this->commentUserID = $commentUserID;
        $this->commentAutor = $commentAutor;
        $this->commentAuthorEmail = $commentAuthorEmail;
        $this->commentStatus = $commentStatus;
        $this->commentContents = $commentContents;
        $this->postID = $postID;
        $this->checkAuthor();
    }

    /**
     * Metodo que establece los datos a preparar.
     */
    protected function prepare() {
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
     * Método que comprueba los datos del autor.
     */
    private function checkAuthor() {
        if (!empty($this->commentUserID)) {
            $user = User::selectByID($this->commentUserID);
            $this->commentAutor = $user->getUserName();
            $this->commentAuthorEmail = $user->getUserEmail();
        }
    }

}
