<?php

/**
 * Modulo del modelo de comentarios.
 * Gestiona la actualización de comentarios.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\base\ModelUpdate;

/**
 * Clase que gestiona la actualización comentarios.
 *
 * @author Nicolás Marulanda P.
 */
class CommentUpdate extends ModelUpdate {

    /** @var Comment Instancia con los datos sin modificar. */
    private $comment;

    /** @var string Nombre del autor. */
    private $commentAuthor;

    /** @var string Email del autor. */
    private $commentAuthorEmail;

    /** @var string Estado del comentario. 0 = Sin aprobar, 1 = Aprobado */
    private $commentStatus;

    /** @var string Contenido del comentario. */
    private $commentContents;

    /**
     * Constructor.
     * @param Comment $comment Instancia con los datos sin modificar.
     * @param string $commentAuthor Nombre del autor.
     * @param string $commentAuthorEmail Email del autor.
     * @param int $commentStatus Estado del comentario. 0 = Sin aprobar, 1 = Aprobado
     * @param string $commentContents Contenido del comentario.
     */
    public function __construct(Comment $comment, $commentAuthor, $commentAuthorEmail, $commentStatus, $commentContents) {
        parent::__construct(Comment::getTableName());
        $this->comment = $comment;
        $this->commentAuthor = $commentAuthor;
        $this->commentAuthorEmail = $commentAuthorEmail;
        $this->commentStatus = $commentStatus;
        $this->commentContents = $commentContents;
    }

    /**
     * Metodo que obtiene el objeto con los datos actualizados.
     * @return Comment
     */
    public function getDataUpdate() {
        //Obtiene el primer dato el cual corresponde al id.
        $id = $this->prepareStatement[0]['value'];

        return Comment::selectByID($id);
    }

    /**
     * Metodo que establece los datos a preparar.
     * @return bool Si es TRUE, no hay datos para actualizar.
     */
    protected function prepare() {
        $this->addPrepare(':' . Comment::ID, $this->comment->getID(), \PDO::PARAM_INT);
        $this->checkFields($this->comment->getCommentAuthor(), $this->commentAuthor, Comment::COMMENT_AUTHOR, \PDO::PARAM_STR);
        $this->checkFields($this->comment->getCommentAuthorEmail(), $this->commentAuthorEmail, Comment::COMMENT_AUTHOR_EMAIL, \PDO::PARAM_STR);
        $this->checkFields($this->comment->getCommentStatus(), $this->commentStatus, Comment::COMMENT_STATUS, \PDO::PARAM_INT);
        $this->checkFields($this->comment->getCommentContents(), $this->commentContents, Comment::COMMENT_CONTENTS, \PDO::PARAM_STR);

        return empty($this->dataColumns);
    }

}
