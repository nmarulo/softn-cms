<?php

/**
 * Modulo del modelo post.
 * Gestiona la lista de Posts.
 */

namespace SoftnCMS\models;

use SoftnCMS\models\Post;

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
        $this->posts = [];
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
            $this->addPost(new Post($value));
        }
    }

    /**
     * Metodo que obtiene los ultimos post.
     * @param int $limit Numero de post.
     * @return array
     */
    public function lastPosts($limit) {
        $output = [];

        if (empty($this->posts)) {
            $select = $this->select('', [], '*', $limit);
            $this->addPosts($select);
            $output = $this->getPosts();
        } else {
            $output = \array_slice($this->getPosts(), 0, $limit, \TRUE);
        }
        return $output;
    }

    public function count() {
        $select = $this->select('', [], 'COUNT(*) AS count');
        return $select[0]['count'];
    }

    public function selectAll() {
        if (!empty($this->posts)) {
            $this->posts = [];
        }
        $select = $this->select();
        $this->addPosts($select);
    }

    /**
     * Metodo que realiza una consulta a la base de datos y obtiene todos los post.
     */
    private function select($where = '', $prepare = [], $columns = '*', $limit = '', $orderBy = 'ID DESC') {
        $db = \SoftnCMS\controllers\DBController::getConnection();
        $table = Post::getTableName();
        $fetch = 'fetchAll';
        return $db->select($table, $fetch, $where, $prepare, $columns, $orderBy, $limit);
    }

}
