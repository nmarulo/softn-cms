<?php

/**
 * Modulo del modelo de comentarios.
 * Gestiona los datos de cada comentario.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;

/**
 * Clase que gestiona los datos de cada comentario.
 *
 * @author Nicolás Marulanda P.
 */
class Comment {

    /** Identificador de la entrada. */
    const ID = 'ID';

    /** Estado del comentario. 0 = Sin aprobar, 1 = Aprobado */
    const COMMENT_STATUS = 'comment_status';

    /** Nombre del autor. */
    const COMMENT_AUTHOR = 'comment_autor';

    /** Email del autor. */
    const COMMENT_AUTHOR_EMAIL = 'comment_author_email';

    /** Fecha de publicación del comentario. */
    const COMMENT_DATE = 'comment_date';

    /** Contenido del comentario. */
    const COMMENT_CONTENTS = 'comment_contents';

    /** Identificador del autor. 0 = para usuarios no registrados. */
    const COMMENT_USER_ID = 'comment_user_ID';

    /** Identificador de la entrada/post. */
    const POST_ID = 'post_ID';

    /** @var string Nombre de la table. */
    private static $TABLE = \DB_PREFIX . 'comments';

    /** @var array Datos del post. */
    private $comment;

    /**
     * Constructor.
     * @param array $data
     */
    public function __construct($data) {
        $this->comment = $data;
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
     * @return Comment
     */
    public static function defaultInstance() {
        $data = [
            self::COMMENT_AUTHOR => '',
            self::COMMENT_AUTHOR_EMAIL => '',
            self::COMMENT_CONTENTS => '',
            self::COMMENT_STATUS => 1,
            self::COMMENT_DATE => '',
            self::POST_ID => 0,
        ];

        return new Comment($data);
    }

    /**
     * Metodo que obtiene un comentario segun su "ID".
     * @param int $value
     * @return Post|bool
     */
    public static function selectByID($value) {
        return self::selectBy($value, self::ID, \PDO::PARAM_INT);
    }

    /**
     * Metodo que obtiene un comentario segun las especificaciones dadas.
     * @param int|string $value Valor a buscar.
     * @param string $column Nombre de la columna en la tabla.
     * @param int $dataType Tipo de dato.
     * @return Comment|bool
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
     * @return Comment|bool En caso de no obtener datos retorna FALSE.
     */
    private static function select($where = '', $prepare = [], $columns = '*', $limit = 1, $orderBy = 'ID DESC') {
        $db = DBController::getConnection();
        $table = self::$TABLE;
        $fetch = 'fetchAll';
        $select = $db->select($table, $fetch, $where, $prepare, $columns, $orderBy, $limit);

        if (empty($select)) {
            return \FALSE;
        }

        return new Comment($select[0]);
    }

    /**
     * Metodo que obtiene el identificador del comentario.
     * @return int
     */
    public function getID() {
        return $this->comment[self::ID];
    }

    /**
     * Metodo que obtiene el estado del comentario. 0 = Sin aprobar, 1 = Aprobado
     * @return int
     */
    public function getCommentStatus() {
        return $this->comment[self::COMMENT_STATUS];
    }

    /**
     * Metodo que obtiene el nombre del autor.
     * @return string
     */
    public function getCommentAutor() {
        return $this->comment[self::COMMENT_AUTHOR];
    }

    /**
     * Metodo que obtiene el Email del autor.
     * @return string
     */
    public function getCommentAuthorEmail() {
        return $this->comment[self::COMMENT_AUTHOR_EMAIL];
    }

    /**
     * Metodo que obtiene la fecha de publicación del comentario.
     * @return string
     */
    public function getCommentDate() {
        return $this->comment[self::COMMENT_DATE];
    }

    /**
     * Metodo que obtiene el contenido del comentario.
     * @return string
     */
    public function getCommentContents() {
        return $this->comment[self::COMMENT_CONTENTS];
    }

    /**
     * Metodo que obtiene el ID del autor del comentario. 0 = para usuarios no registrados.
     * @return int
     */
    public function getCommentUserID() {
        return $this->comment[self::COMMENT_USER_ID];
    }

    /**
     * Metodo que obtiene el ID de la entrada/post.
     * @return int
     */
    public function getPostID() {
        return $this->comment[self::POST_ID];
    }

}
