<?php

/**
 * Gestión de entradas.
 * @package SoftN-CMS\sn-includes
 */

/**
 * Clase para implementar las entradas como objetos.
 * @author Nicolás Marulanda P.
 */
class SN_Posts {

    /** @var int Identificador de la entrada. */
    private $ID;

    /** @var string Titulo. */
    private $post_title;

    /** @var int Estado. 0 = Borrador, 1 = Publicado. */
    private $post_status;

    /** @var date Fecha de publicación. */
    private $post_date;

    /** @var date Fecha de actualización. */
    private $post_update;

    /** @var string Contenido. */
    private $post_contents;

    /** @var int Estado de los comentarios. 0 = Deshabilitado, 1 = Habilitado */
    private $comment_status;

    /** @var int Número de comentarios. */
    private $comment_count;

    /** @var int Identificador del autor. */
    private $users_ID;

    /** @var strin Tipo de entrada. 'post' = entrada, 'page' = pagina */
    private $post_type;

    /**
     * Constructor.
     * @param array|PDOStatement $arg Datos de la entrada.<br/>
     * <b>NOTA: Los indices del array deben corresponder con el nombre de la tabla.</b>
     */
    public function __construct($arg) {
        if (is_object($arg)) {
            $this->ID = $arg->ID;
            $this->post_title = $arg->post_title;
            $this->post_status = $arg->post_status;
            $this->post_date = $arg->post_date;
            $this->post_update = $arg->post_update;
            $this->post_contents = $arg->post_contents;
            $this->comment_status = $arg->comment_status;
            $this->comment_count = $arg->comment_count;
            $this->users_ID = $arg->users_ID;
            $this->post_type = $arg->post_type;
        } elseif (is_array($arg)) {
            $default = array(
                'ID' => 0,
                'post_title' => '',
                'post_status' => 1,
                'post_date' => '0000-00-00 00:00:00',
                'post_update' => '0000-00-00 00:00:00',
                'post_contents' => '',
                'comment_status' => 1,
                'comment_count' => 0,
                'users_ID' => 1,
                'post_type' => 'post'
            );

            $default = array_merge($default, $arg);

            $this->ID = $default['ID'];
            $this->post_title = $default['post_title'];
            $this->post_status = $default['post_status'];
            $this->post_date = $default['post_date'];
            $this->post_update = $default['post_update'];
            $this->post_contents = $default['post_contents'];
            $this->comment_status = $default['comment_status'];
            $this->comment_count = $default['comment_count'];
            $this->users_ID = $default['users_ID'];
            $this->post_type = $default['post_type'];
        } else {
            echo 'ERROR. Tipo de parametro incorrecto.';
        }
    }

    /**
     * Metodo que obtiene las publicaciones de un usuario ordenados por fecha 
     * de forma descendiente.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param int $userID Identificador del usuario.
     * @param string $post_type [Opcional] Tipo de publicación. 'page' o por defecto 'post'.
     * @return object
     */
    public static function getPostsByAuthor($userID, $post_type = 'post') {
        global $sndb;

        return $sndb->query([
                    'table' => 'posts',
                    'where' => 'users_ID = :users_ID AND post_type = :post_type',
                    'prepare' => [
                        [':users_ID', $userID],
                        [':post_type', $post_type],
                    ],
                    'orderBy' => 'post_date DESC',
        ]);
    }

    /**
     * Metodo que obtiene el número de publicaciones realizadas por un usuario.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param int $id Identificador del usuario.
     * @return int
     */
    public static function user_posts_count($id) {
        global $sndb;

        $out = $sndb->query([
            'table' => 'posts',
            'column' => 'count(*) as count',
            'where' => 'users_ID = :id',
            'prepare' => [[':id', $id,],],
                ], 'fetchObject');

        if ($out) {
            $out = $out->count;
        }

        return $out;
    }

    /**
     * Metodo que obtiene una lista con las publicaciones que contienen 
     * el texto pasado por parametro.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param string $str
     * @param string $post_type Tipo de entrada.
     * @return array
     */
    public static function search($str, $post_type) {
        global $sndb;

        return $sndb->query([
                    'table' => 'posts',
                    'where' => 'post_title LIKE :post_title AND post_type = :post_type',
                    'orderBy' => 'ID DESC',
                    'prepare' => [
                        [':post_title', '%' . $str . '%'],
                        [':post_type', $post_type],
                    ]
                        ], 'fetchAll');
    }

    /**
     * Metodo que borra una publicación segun su id.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @global array $dataTable Lista de datos de uso común.
     * @param int $id Identificador de la publicación.
     * @param string $post_type [Opcional] Tipo de publicación. Se usa para actualizar "$dataTable".
     * @return bool
     */
    public static function delete($id, $post_type = '') {
        global $sndb, $dataTable;

        $out = $sndb->exec([
            'type' => 'DELETE',
            'table' => 'posts',
            'where' => 'ID = :ID',
            'prepare' => [[':ID', $id]],
        ]);

        if ($out) {
            switch ($post_type) {
                case 'post':
                    $dataTable['post']['dataList'] = SN_Posts::dataList('fetchAll', $post_type);
                    break;
                case 'page':
                    $dataTable['page']['dataList'] = SN_Posts::dataList('fetchAll', $post_type);
                    break;
            }
        }

        return $out;
    }

    /**
     * Metodo que obtiene todas las publicaciones ordenadas por ID de forma descendente.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param string $fetch [Opcional] Tipo de datos a retornar.
     * Con "fetchObject" para retornar los datos como objetos. 
     * Por defecto, "fetchAll", retorna un array asociativo.
     * @param string $post_type [Opcional] Tipo de publicación. 'post' o 'page'.
     * @return array|object
     */
    public static function dataList($fetch = 'fetchAll', $post_type = 0) {
        global $sndb;

        //Si $fetch es false retorno el Object de la consulta.
        $fetch = empty($fetch) ? null : $fetch;
        $where = '';
        $prepare = [];
        if ($post_type) {
            $where = 'post_type = :post_type';
            $prepare[] = [':post_type', $post_type];
        }

        $out = $sndb->query([
            'table' => 'posts',
            'orderBy' => 'ID DESC',
            'where' => $where,
            'prepare' => $prepare,
                ], $fetch);

        return $out;
    }

    /**
     * Metodo que obtiene todas las publicaciones ordenadas por ID de forma descendente 
     * de un usuario.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param int $author Identificador del autor.
     * @param string $post_type [Opcional] Tipo de publicación. 'post' o 'page'.
     * @param string $fetch [Opcional] Tipo de datos a retornar.
     * Con "fetchObject" para retornar los datos como objetos. 
     * Por defecto, "fetchAll", retorna un array asociativo.
     * @return array|object
     */
    public static function dataListByAuthor($author, $post_type = 0, $fetch = 'fetchAll') {
        global $sndb;

        //Si $fetch es false retorno el Object de la consulta.
        $fetch = empty($fetch) ? null : $fetch;
        $where = 'users_ID = :users_ID';
        $prepare = [[':users_ID', $author,],];
        if ($post_type) {
            $where .= ' AND post_type = :post_type';
            $prepare[] = [':post_type', $post_type];
        }

        $out = $sndb->query([
            'table' => 'posts',
            'orderBy' => 'ID DESC',
            'where' => $where,
            'prepare' => $prepare,
                ], $fetch);

        return $out;
    }

    /**
     * Metodo que obtiene una publicación segun su ID y retorna 
     * un instancia SN_Posts con los datos.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param int $id Identificador de la publicación.
     * @param string $fetch [Opcional]
     * @return object|PDOStatement
     */
    public static function get_instance($id, $fetch = 'object') {
        global $sndb;

        $out = $sndb->query(array(
            'table' => 'posts',
            'where' => 'ID = :id',
            'prepare' => [[':id', $id]]
        ));

        if ($out) {
            switch ($fetch) {
                case 'object':
                    $out = new SN_Posts($out->fetchObject());
                    break;
                case 'PDOStatement':
                    // $out
                    break;
            }
        }

        return $out;
    }

    /**
     * Metodo que obtiene la ultima publicación.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @return object Retorna un objeto PDOstatement.
     */
    public static function get_lastInsert() {
        global $sndb;

        return $sndb->query(array(
                    'table' => 'posts',
                    'orderBy' => 'ID DESC',
                    'limit' => '1'
                        ), 'fetchObject');
    }

    /**
     * Metodo que agrega los datos de la publicación a la base de datos.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @return bool
     */
    public function insert() {
        global $sndb;

        $out = $sndb->exec([
            'type' => 'INSERT',
            'table' => 'posts',
            'column' => 'post_title, post_status, post_date, post_update, post_contents, comment_status, comment_count, users_ID, post_type',
            'values' => ':post_title, :post_status, :post_date, :post_update, :post_contents, :comment_status, :comment_count, :users_ID, :post_type',
            'prepare' => [
                [':post_title', $this->post_title],
                [':post_status', $this->post_status],
                [':post_date', $this->post_date],
                [':post_update', $this->post_update],
                [':post_contents', $this->post_contents],
                [':comment_status', $this->comment_status],
                [':comment_count', $this->comment_count],
                [':users_ID', $this->users_ID],
                [':post_type', $this->post_type],
            ],
        ]);

        if ($out) {
            $out = SN_Posts::get_lastInsert();
            if ($out) {
                $this->ID = $out->ID;
                $out = true;
            }
        }

        return $out;
    }

    /**
     * Metodo que actualiza los datos de la publicación.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @return bool
     */
    public function update() {
        global $sndb;

        return $sndb->exec([
                    'type' => 'UPDATE',
                    'table' => 'posts',
                    'set' => 'post_title = :post_title, post_status = :post_status, post_update = :post_update, post_contents = :post_contents, comment_status = :comment_status, users_ID = :users_ID, post_type = :post_type',
                    'where' => 'ID = :id',
                    'prepare' => [
                        [':id', $this->ID],
                        [':post_title', $this->post_title],
                        [':post_status', $this->post_status],
                        [':post_update', $this->post_update],
                        [':post_contents', $this->post_contents],
                        [':comment_status', $this->comment_status],
                        [':users_ID', $this->users_ID],
                        [':post_type', $this->post_type],
                    ]
        ]);
    }

    /**
     * Metodo que obtiene el identificador de la publicación.
     * @return int
     */
    public function getID() {
        return $this->ID;
    }

    /**
     * Metodo que obtiene el titulo de la publicación.
     * @return string
     */
    public function getPost_title() {
        return $this->post_title;
    }

    /**
     * Metodo que obtiene el estado. 0 = Borrador, 1 = Publicado.
     * @return int
     */
    public function getPost_status() {
        return $this->post_status;
    }

    /**
     * Metodo que obtiene la fecha de publicación.
     * @return string
     */
    public function getPost_date() {
        return $this->post_date;
    }

    /**
     * Metodo que obtiene la fecha de actualización.
     * @return string
     */
    public function getPost_update() {
        return $this->post_update;
    }

    /**
     * Metodo que obtiene el contenido de la publicación.
     * @return string
     */
    public function getPost_contents() {
        return $this->post_contents;
    }

    /**
     * Metodo que obtiene el estado de los comentarios. 0 = Deshabilitado, 1 = Habilitado.
     * @return type
     */
    public function getComment_status() {
        return $this->comment_status;
    }

    /**
     * Metodo que obtiene el número de comentarios.
     * @return type
     */
    public function getComment_count() {
        return $this->comment_count;
    }

    /**
     * Metodo que obtiene el identificador del autor.
     * @return int
     */
    public function getUsers_ID() {
        return $this->users_ID;
    }

    /**
     * Metodo que obtiene el tipo de publicación.
     * @return string
     */
    public function getPost_type() {
        return $this->post_type;
    }

}
