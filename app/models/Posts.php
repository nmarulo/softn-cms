<?php

/**
 * Modulo del modelo post.
 * Gestiona la lista de Posts.
 */

namespace SoftnCMS\models;

use SoftnCMS\controllers\Post;

/**
 * Clase que gestiona la lista con todos los posts de la base de datos.
 *
 * @author Nicolás Marulanda P.
 */
class Posts {

    /**
     * Lista de posts, donde el indice o clave corresponde al ID.
     * @var array 
     */
    private $posts;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->select();
    }

    /**
     * Metodo que obtiene todos los posts.
     * @return array
     */
    public function getPosts() {
        return $this->posts;
    }

    /**
     * Metodo que obtiene, segun su ID, un post.
     * @param int $id
     * @return Post
     */
    public function getPost($id) {
        return $this->posts[$id];
    }

    /**
     * Metodo que agrega un post a la lista.
     * @param Post $post
     */
    public function addPost(Post $post) {
        $this->posts[$post->getID()] = $post;
    }

    /**
     * Metodo que obtiene un array con los datos de los post y los agrega a la lista.
     * @param array $post
     */
    public function addPosts($post) {
        foreach ($post as $value) {
            $this->posts[$value['ID']] = new Post($value);
        }
    }

    /**
     * Metodo que realiza una consulta a la base de datos y obtiene todos los post.
     */
    private function select() {
        $db = new \SoftnCMS\models\MySql();
        $db = $db->getConnection();
        $this->addPosts($db->query('SELECT * FROM sn_posts limit 5')->fetchAll());
    }

}
