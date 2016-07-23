<?php

/**
 * Modulo del controlador post.
 * Gestiona la información del post.
 */

namespace SoftnCMS\controllers;

use SoftnCMS\models\Users;

/**
 * Clase que gestiona la información de cada uno de los posts.
 *
 * @author Nicolás Marulanda P.
 */
class Post {

    /** Identificador de la entrada. */
    const ID = 'ID';

    /** Titulo. */
    const POST_TITLE = 'post_title';

    /** Estado. 0 = Borrador, 1 = Publicado. */
    const POST_STATUS = 'post_status';

    /** Fecha de publicación. */
    const POST_DATE = 'post_date';

    /** Fecha de actualización. */
    const POST_UPDATE = 'post_update';

    /** Contenido. */
    const POST_CONTENTS = 'post_contents';

    /** Estado de los comentarios. 0 = Deshabilitado, 1 = Habilitado */
    const COMMENT_STATUS = 'comment_status';

    /** Número de comentarios. */
    const COMMENT_COUNT = 'comment_count';

    /** Identificador del autor. */
    const USER_ID = 'users_ID';

    /**
     * Datos del post.
     * @var array
     */
    private $post;

    /**
     * Constructor
     * @param array $data
     */
    public function __construct($data) {
        $this->post = $data;
    }

    /**
     * Metodo que obtiene el identificador de la publicación.
     * @return int
     */
    public function getID() {
        return $this->post[Post::ID];
    }

    /**
     * Metodo que obtiene el titulo de la publicación.
     * @return string
     */
    public function getPostTitle() {
        return $this->post[Post::POST_TITLE];
    }

    /**
     * Metodo que obtiene el estado. 0 = Borrador, 1 = Publicado.
     * @return int
     */
    public function getPostStatus() {
        return $this->post[Post::POST_STATUS];
    }

    /**
     * Metodo que obtiene la fecha de publicación.
     * @return string
     */
    public function getPostDate() {
        return $this->post[Post::POST_DATE];
    }

    /**
     * Metodo que obtiene la fecha de actualización.
     * @return string
     */
    public function getPostUpdate() {
        return $this->post[Post::POST_UPDATE];
    }

    /**
     * Metodo que obtiene el contenido de la publicación.
     * @return string
     */
    public function getPostContents() {
        return $this->post[Post::POST_CONTENTS];
    }

    /**
     * Metodo que obtiene el estado de los comentarios. 0 = Deshabilitado, 1 = Habilitado.
     * @return int
     */
    public function getCommentStatus() {
        return $this->post[Post::COMMENT_STATUS];
    }

    /**
     * Metodo que obtiene el número de comentarios.
     * @return int
     */
    public function getCommentCount() {
        return $this->post[Post::COMMENT_COUNT];
    }

    /**
     * Metodo que obtiene el identificador del autor.
     * @return int
     */
    public function getUserID() {
        return $this->post[Post::USER_ID];
    }

    /**
     * Metodo que obtiene una instancia del autor.
     * @return User
     */
    public function getUser() {
        $userID = $this->post[Post::USER_ID];
        $users = new Users();
        return $users->getUser($userID);
    }

}
