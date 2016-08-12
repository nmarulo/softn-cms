<?php

/**
 * Modulo del modelo post.
 * Gestiona la actualización de posts.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\Post;
use SoftnCMS\controllers\DBController;

/**
 * Clase que gestiona la actualización posts.
 *
 * @author Nicolás Marulanda P.
 */
class PostUpdate {

    /** @var Post Instancia con los datos sin modificar. */
    private $post;

    /** @var string Titulo. */
    private $postTitle;

    /** @var string Contenido. */
    private $postContents;

    /** @var int Estado de los comentarios. */
    private $commentStatus;

    /** @var int Estado. */
    private $postStatus;

    /** @var string Campos que seran actualizados. */
    private $dataColumns;

    /** @var array Lista con los indices, valores y tipos de datos para la consulta. */
    private $prepareStatement;

    /**
     * Constructor.
     * @param Post $post Instancia con los datos sin modificar.
     * @param type $postTitle Titulo.
     * @param type $postContents Contenido.
     * @param type $commentStatus Estado de los comentarios.
     * @param type $postStatus Estado.
     */
    public function __construct(Post $post, $postTitle, $postContents, $commentStatus, $postStatus) {
        $this->post = $post;
        $this->postTitle = $postTitle;
        $this->postContents = $postContents;
        $this->commentStatus = $commentStatus;
        $this->postStatus = $postStatus;
        $this->prepareStatement = [];
        $this->dataColumns = "";
    }

    /**
     * Metodo que actualiza los datos del post en la base de datos.
     * @return bool Si es TRUE, todo se realizo correctamente.
     */
    public function update() {
        $db = DBController::getConnection();
        $table = Post::getTableName();
        $parameter = ':id';
        $where = "ID = $parameter";
        $newData = $this->post->getID();
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
     * Metodo que obtiene el post con los datos actualizados.
     * @return Post
     */
    public function getPost() {
        $db = DBController::getConnection();
        $columns = '*';
        $where = 'ID = :id';
        $fetch = 'fetchAll';
        $table = Post::getTableName();
        //Obtiene el primer dato el cual corresponde al id.
        $prepare = [$this->prepareStatement[0]];
        $select = $db->select($table, $fetch, $where, $prepare, $columns);
        $post = new Post($select[0]);

        return $post;
    }

    /**
     * Metodo que establece los datos a preparar.
     * @return bool Si es TRUE, no hay datos para actualizar.
     */
    private function prepare() {
        $postUpdate = \date('Y-m-d H:i:s', \time());

        $this->checkFields($this->post->getPostTitle(), $this->postTitle, Post::POST_TITLE, \PDO::PARAM_STR);
        $this->checkFields($this->post->getPostContents(), $this->postContents, Post::POST_CONTENTS, \PDO::PARAM_STR);
        $this->checkFields($this->post->getCommentStatus(), $this->commentStatus, Post::COMMENT_STATUS, \PDO::PARAM_STR);
        $this->checkFields($this->post->getPostStatus(), $this->postStatus, Post::POST_STATUS, \PDO::PARAM_INT);
        $this->checkFields($this->post->getPostUpdate(), $postUpdate, Post::POST_UPDATE, \PDO::PARAM_STR);

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
