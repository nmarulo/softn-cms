<?php

/**
 * Modulo del modelo post.
 * Gestiona la actualización de posts.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\Post;
use SoftnCMS\models\admin\base\ModelUpdate;

/**
 * Clase que gestiona la actualización posts.
 *
 * @author Nicolás Marulanda P.
 */
class PostUpdate extends ModelUpdate {

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

    /**
     * Constructor.
     * @param Post $post Instancia con los datos sin modificar.
     * @param string $postTitle Titulo.
     * @param string $postContents Contenido.
     * @param int $commentStatus Estado de los comentarios.
     * @param int $postStatus Estado.
     */
    public function __construct(Post $post, $postTitle, $postContents, $commentStatus, $postStatus) {
        parent::__construct(Post::getTableName());
        $this->post = $post;
        $this->postTitle = $postTitle;
        $this->postContents = $postContents;
        $this->commentStatus = $commentStatus;
        $this->postStatus = $postStatus;
    }

    /**
     * Metodo que obtiene el post con los datos actualizados.
     * @return Post
     */
    public function getDataUpdate() {
        //Obtiene el primer dato el cual corresponde al id.
        $id = $this->prepareStatement[0]['value'];

        return Post::selectByID($id);
    }

    /**
     * Metodo que establece los datos a preparar.
     * @return bool Si es TRUE, no hay datos para actualizar.
     */
    protected function prepare() {
        $date = \date('Y-m-d H:i:s', \time());
        $this->addPrepare(':' . Post::ID, $this->post->getID(), \PDO::PARAM_INT);
        $this->checkFields($this->post->getPostTitle(), $this->postTitle, Post::POST_TITLE, \PDO::PARAM_STR);
        $this->checkFields($this->post->getPostContents(), $this->postContents, Post::POST_CONTENTS, \PDO::PARAM_STR);
        $this->checkFields($this->post->getCommentStatus(), $this->commentStatus, Post::COMMENT_STATUS, \PDO::PARAM_STR);
        $this->checkFields($this->post->getPostStatus(), $this->postStatus, Post::POST_STATUS, \PDO::PARAM_INT);
        $this->checkFields($this->post->getPostUpdate(), $date, Post::POST_UPDATE, \PDO::PARAM_STR);

        return empty($this->dataColumns);
    }

}
