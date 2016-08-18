<?php

/**
 * Modulo del modelo POST.
 * Gestiona los datos de cada POST.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\User;
use SoftnCMS\controllers\DBController;

/**
 * Clase que gestiona los datos de cada POST.
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
    const USER_ID = 'user_ID';

    /** @var string Nombre de la table. */
    private static $TABLE = \DB_PREFIX . 'posts';
    
    /** @var array Datos del post. */
    private $post;

    /**
     * Constructor.
     * @param array $data
     */
    public function __construct($data) {
        $this->post = $data;
    }

    /**
     * Metodo que obtiene el nombre de la tabla.
     * @return string
     */
    public static function getTableName() {
        return self::$TABLE;
    }

    /**
     *  Metodo que retorna una instancia por defecto.
     * @return Post
     */
    public static function defaultInstance() {
        $data = [
            Post::ID => 0,
            Post::POST_TITLE => '',
            Post::POST_STATUS => 1,
            Post::POST_CONTENTS => '',
            Post::POST_DATE => '0000-00-00 00:00:00',
            Post::POST_UPDATE => '0000-00-00 00:00:00',
            Post::COMMENT_COUNT => 0,
            Post::COMMENT_STATUS => 1,
            Post::USER_ID => 0,
        ];

        return new Post($data);
    }

    /**
     * Metodo que obtiene un post segun su "ID".
     * @param int $value
     * @return Post|bool
     */
    public static function selectByID($value) {
        return self::selectBy($value, Post::ID, \PDO::PARAM_INT);
    }

    /**
     * Metodo que obtiene un post segun las especificaciones dadas.
     * @param int|string $value Valor a buscar.
     * @param string $column Nombre de la columna en la tabla.
     * @param int $dataType Tipo de dato.
     * @return Post|bool
     */
    private static function selectBy($value, $column, $dataType = \PDO::PARAM_STR) {
        $parameter = ":$column";
        $where = "$column = $parameter";
        $prepare[] = DBController::prepareStatement($parameter, $value, $dataType);

        return self::select($where, $prepare);
    }

    /**
     * Metodo que realiza una consulta a la base de datos.
     * @param string $where [Opcional] Condiciones.
     * @param array $prepare [Opcional] Lista de indices a reemplazar en la consulta.
     * @param string $columns [Opcional] Por defecto "*". Columnas.
     * @param int $limit [Opcional] Por defecto 1. Numero de datos a retornar.
     * @param string $orderBy [Opcional] Por defecto "ID DESC". Ordenar por.
     * @return Post|bool En caso de no obtener datos retorna FALSE.
     */
    private static function select($where = '', $prepare = [], $columns = '*', $limit = 1, $orderBy = 'ID DESC') {
        $db = DBController::getConnection();
        $table = self::$TABLE;
        $fetch = 'fetchAll';
        $select = $db->select($table, $fetch, $where, $prepare, $columns, $orderBy, $limit);

        if (empty($select)) {
            return \FALSE;
        }

        return new Post($select[0]);
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
        $userID = $this->getUserID();
        
        return User::selectByID($userID);
    }

    /**
     * Metodo que establece el titulo del post.
     * @param string $title
     */
    public function setPostTitle($title) {
        $this->post[Post::POST_TITLE] = $title;
    }

}
