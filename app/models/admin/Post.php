<?php

/**
 * Modulo del modelo POST.
 * Gestiona los datos de cada POST.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\User;
use SoftnCMS\models\admin\base\Model;

/**
 * Clase que gestiona los datos de cada POST.
 *
 * @author Nicolás Marulanda P.
 */
class Post extends Model {

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
     * Metodo que retorna una instancia por defecto.
     * @return Post
     */
    public static function defaultInstance() {
        $data = [
            self::ID => 0,
            self::POST_TITLE => '',
            self::POST_STATUS => 1,
            self::POST_CONTENTS => '',
            self::POST_DATE => '0000-00-00 00:00:00',
            self::POST_UPDATE => '0000-00-00 00:00:00',
            self::COMMENT_COUNT => 0,
            self::COMMENT_STATUS => 1,
            self::USER_ID => 0,
        ];

        return new Post($data);
    }

    /**
     * Metodo que obtiene un post segun su "ID".
     * @param int $value Identificador del post.
     * @return Post|bool Si es FALSE, no hay datos.
     */
    public static function selectByID($value) {
        $select = self::selectBy(self::$TABLE, $value, self::ID, \PDO::PARAM_INT);

        return self::getInstanceData($select);
    }
    
    /**
     * Metodo que recibe un lista de datos y retorna un instancia.
     * @param array $data Lista de datos.
     * @return Post|bool Si es FALSE, no hay datos.
     */
    public static function getInstanceData($data) {
        return parent::getInstance($data, __CLASS__);
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
