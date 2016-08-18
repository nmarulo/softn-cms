<?php

/**
 * Modulo del modelo de comentarios.
 * Gestiona la actualización de comentarios.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\Comment;

/**
 * Clase que gestiona la actualización comentarios.
 *
 * @author Nicolás Marulanda P.
 */
class CommentUpdate {

    /** @var Comment Instancia con los datos sin modificar. */
    private $comment;

    /** @var string Campos que seran actualizados. */
    private $dataColumns;

    /** @var array Lista con los indices, valores y tipos de datos para la consulta. */
    private $prepareStatement;

    /** @var string Nombre del autor. */
    private $commentAutor;

    /** @var string Email del autor. */
    private $commentAuthorEmail;

    /** @var string Estado del comentario. 0 = Sin aprobar, 1 = Aprobado */
    private $commentStatus;

    /** @var string Contenido del comentario. */
    private $commentContents;

    /**
     * Constructor.
     * @param Comment $comment Instancia con los datos sin modificar.
     * @param type $commentAutor Nombre del autor.
     * @param type $commentAuthorEmail mail del autor.
     * @param type $commentStatus Estado del comentario. 0 = Sin aprobar, 1 = Aprobado
     * @param type $commentContents Contenido del comentario.
     */
    public function __construct(Comment $comment, $commentAutor, $commentAuthorEmail, $commentStatus, $commentContents) {
        $this->comment = $comment;
        $this->prepareStatement = [];
        $this->commentAutor = $commentAutor;
        $this->commentAuthorEmail = $commentAuthorEmail;
        $this->commentStatus = $commentStatus;
        $this->commentContents = $commentContents;
    }

    /**
     * Metodo que actualiza los datos del comentario en la base de datos.
     * @return bool Si es TRUE, todo se realizo correctamente.
     */
    public function update() {
        $db = DBController::getConnection();
        $table = Comment::getTableName();
        $parameter = ':id';
        $where = "ID = $parameter";
        $newData = $this->comment->getID();
        $dataType = \PDO::PARAM_INT;
        $this->addPrepare($parameter, $newData, $dataType);

        /*
         * Si no hay datos, no se ejecuta la consulta. 
         * Se retorna TRUE para evitar un error.
         */
        if ($this->prepare()) {
            return \TRUE;
        }

        return $db->update($table, $this->dataColumns, $where, $this->prepareStatement);
    }

    /**
     * Metodo que obtiene el comentario con los datos actualizados.
     * @return Comment
     */
    public function getComment() {
        $db = DBController::getConnection();
        $columns = '*';
        $where = 'ID = :id';
        $fetch = 'fetchAll';
        $table = Comment::getTableName();
        //Obtiene el primer dato el cual corresponde al id.
        $prepare = [$this->prepareStatement[0]];
        $select = $db->select($table, $fetch, $where, $prepare, $columns);
        $post = new Comment($select[0]);

        return $post;
    }

    /**
     * Metodo que establece los datos a preparar.
     * @return bool Si es TRUE, no hay datos para actualizar.
     */
    private function prepare() {
        $this->checkFields($this->comment->getCommentAutor(), $this->commentAutor, Comment::COMMENT_AUTHOR, \PDO::PARAM_STR);
        $this->checkFields($this->comment->getCommentAuthorEmail(), $this->commentAuthorEmail, Comment::COMMENT_AUTHOR_EMAIL, \PDO::PARAM_STR);
        $this->checkFields($this->comment->getCommentStatus(), $this->commentStatus, Comment::COMMENT_STATUS, \PDO::PARAM_INT);
        $this->checkFields($this->comment->getCommentContents(), $this->commentContents, Comment::COMMENT_CONTENTS, \PDO::PARAM_STR);

        return empty($this->dataColumns);
    }

    /**
     * Metodo que comprueba si el nuevo dato es diferente al de la base de datos, 
     * de ser asi el campo sera actualizado.
     * @param string|int $oldData Dato actual.
     * @param string|int $newData Dato nuevo.
     * @param string $column Nombre de la columna en la tabla.
     * @param int $dataType Tipo de dato.
     */
    private function checkFields($oldData, $newData, $column, $dataType) {
        if ($oldData != $newData) {
            $parameter = ':' . $column;
            $this->addSetDataSQL($column, $parameter);
            $this->addPrepare($parameter, $newData, $dataType);
        }
    }

    /**
     * Metodo que agrega los datos que seran actualizados.
     * @param string $column Nombre de la columna en la tabla.
     * @param string $data Nuevo valor.
     */
    private function addSetDataSQL($column, $data) {
        $this->dataColumns .= empty($this->dataColumns) ? '' : ', ';
        $this->dataColumns .= "$column = $data";
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
