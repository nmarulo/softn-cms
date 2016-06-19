<?php

/**
 * Gestión de comentarios.
 * @package SoftN-CMS\sn-includes
 */

/**
 * Clase para implementar los comentarios como objetos.
 * @author Nicolás Marulanda P.
 */
class SN_Comments {

    /** @var int Identificador del comentario. */
    private $ID;

    /** @var int Estado del comentario. 0 = Sin aprobar, 1 = Aprobado */
    private $comment_status;

    /** @var string Nombre del autor. */
    private $comment_autor;

    /** @var string Email del autor. */
    private $comment_author_email;

    /** @var date Fecha de publicación del comentario. */
    private $comment_date;

    /** @var string Contenido del comentario. */
    private $comment_contents;

    /** @var int Identificador del autor. 0 = para usuarios no registrados. */
    private $comment_user_ID;

    /** @var int Identificador de la entrada/post. */
    private $post_ID;

    /**
     * Constructor.
     * @param array|PDOStatement $arg Datos del comentario.<br/>
     * <b>NOTA: Los indices del array deben corresponder con el nombre de la tabla.</b>
     */
    public function __construct($arg) {
        if (is_object($arg)) {
            $this->ID = $arg->ID;
            $this->comment_status = $arg->comment_status;
            $this->comment_autor = $arg->comment_autor;
            $this->comment_author_email = $arg->comment_author_email;
            $this->comment_date = $arg->comment_date;
            $this->comment_contents = $arg->comment_contents;
            $this->comment_user_ID = $arg->comment_user_ID;
            $this->post_ID = $arg->post_ID;
        } elseif (is_array($arg)) {
            $default = array(
                'ID' => 0,
                'comment_status' => 1,
                'comment_autor' => '',
                'comment_author_email' => '',
                'comment_date' => '0000-00-00 00:00:00',
                'comment_contents' => '',
                'comment_user_ID' => 0,
                'post_ID' => 0
            );

            $default = array_merge($default, $arg);

            $this->ID = $default['ID'];
            $this->comment_status = $default['comment_status'];
            $this->comment_autor = $default['comment_autor'];
            $this->comment_author_email = $default['comment_author_email'];
            $this->comment_date = $default['comment_date'];
            $this->comment_contents = $default['comment_contents'];
            $this->comment_user_ID = $default['comment_user_ID'];
            $this->post_ID = $default['post_ID'];
        } else {
            echo 'ERROR. Tipo de parametro incorrecto.';
        }
    }

    /**
     * Metodo que obtiene una lista con los comentarios que contienen 
     * el texto pasado por parametro.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param string $str
     * @return array
     */
    public static function search($str) {
        global $sndb;

        return $sndb->query([
                    'table' => 'comments',
                    'where' => 'comment_contents LIKE :comment_contents',
                    'orderBy' => 'ID DESC',
                    'prepare' => [[':comment_contents', '%' . $str . '%'],]
                        ], 'fetchAll');
    }

    /**
     * Metodo que borra un comentario segun su id.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @global array $dataTable Lista de datos de uso común.
     * @param int $id
     * @return bool
     */
    public static function delete($id) {
        global $sndb, $dataTable;

        $out = $sndb->exec([
            'type' => 'DELETE',
            'table' => 'comments',
            'where' => 'ID = :ID',
            'prepare' => [[':ID', $id],],
        ]);

        if ($out) {
            $dataTable['comment']['dataList'] = SN_Comments::dataList();
        }

        return $out;
    }

    /**
     * Metodo que obtiene todos los comentarios ordenados por su ID de forma descendente.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param string $fetch [Opcional] Tipo de datos a retornar.
     * Con "fetchObject" para retornar los datos como objetos. 
     * Por defecto, "fetchAll", retorna un array asociativo.
     * @param string $where [Opcional] Condiciones de la consulta.
     * @param array $prepare [Opcional] Inidices de las condiciones.
     * @return array|object
     */
    public static function dataList($fetch = 'fetchAll', $where = 0, $prepare = []) {
        global $sndb;

        $sql = [
            'table' => 'comments',
            'orderBy' => 'ID DESC'
        ];
        
        if ($where) {
            $sql['where'] = $where;
            $sql['prepare'] = $prepare;
        }
        
        return $sndb->query($sql, $fetch);
    }
    
    /**
     * Metodo que obtiene todos los comentarios realizado a una publicación.
     * @param int $id Identificador de la publicación.
     * @return array
     */
    public static function dataListByPost($id){
        return self::dataList('fetchAll', 'post_ID = :post_ID', [[':post_ID', $id]]);
    }

    /**
     * Metodo que obtiene un comentario segun su ID y retorna 
     * un instancia SN_Comments con los datos.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param int $id
     * @return object
     */
    public static function get_instance($id) {
        global $sndb;

        $out = $sndb->query(array(
            'table' => 'comments',
            'where' => 'ID = :ID',
            'prepare' => [[':ID', $id],],
                ), 'fetchObject');

        if ($out) {
            $out = new SN_Comments($out);
        }

        return $out;
    }

    /**
     * Metodo que obtiene el ultimo comentario.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @return object Retorna un objeto PDOstatement
     */
    public static function get_lastInsert() {
        global $sndb;

        return $sndb->query(array(
                    'table' => 'comments',
                    'orderBy' => 'ID DESC',
                    'limit' => '1'
                        ), 'fetchObject');
    }

    /**
     * Metodo que agrega los datos del comentario a la base de datos.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @return bool
     */
    public function insert() {
        global $sndb;

        return $sndb->exec(array(
                    'table' => 'comments',
                    'column' => 'comment_status, comment_autor, comment_author_email, comment_date, comment_contents, comment_user_ID, post_ID',
                    'values' => ':comment_status, :comment_autor, :comment_author_email, :comment_date, :comment_contents, :comment_user_ID, :post_ID',
                    'prepare' => [
                        [':comment_status', $this->comment_status],
                        [':comment_autor', $this->comment_autor],
                        [':comment_author_email', $this->comment_author_email],
                        [':comment_date', $this->comment_date],
                        [':comment_contents', $this->comment_contents],
                        [':comment_user_ID', $this->comment_user_ID],
                        [':post_ID', $this->post_ID],
                    ],
        ));
    }

    /**
     * Metodo que actualiza los datos del comentario.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @return bool
     */
    public function update() {
        global $sndb;

        return $sndb->exec([
                    'type' => 'UPDATE',
                    'table' => 'comments',
                    'set' => 'comment_status = :comment_status, comment_contents = :comment_contents',
                    'where' => 'ID = :ID',
                    'prepare' => [
                        [':ID', $this->ID],
                        [':comment_status', $this->comment_status],
                        [':comment_contents', $this->comment_contents],
                    ],
        ]);
    }

    /**
     * Metodo que obtiene el identificador del comentario.
     * @return int
     */
    public function getID() {
        return $this->ID;
    }

    /**
     * Metodo que obtiene el estado del comentario. 0 = Sin aprobar, 1 = Aprobado
     * @return int
     */
    public function getComment_status() {
        return $this->comment_status;
    }

    /**
     * Metodo que obtiene el nombre del autor.
     * @return string
     */
    public function getComment_autor() {
        return $this->comment_autor;
    }

    /**
     * Metodo que obtiene el Email del autor.
     * @return string
     */
    public function getComment_author_email() {
        return $this->comment_author_email;
    }

    /**
     * Metodo que obtiene la fecha de publicación del comentario.
     * @return string
     */
    public function getComment_date() {
        return $this->comment_date;
    }

    /**
     * Metodo que obtiene el contenido del comentario.
     * @return string
     */
    public function getComment_contents() {
        return $this->comment_contents;
    }

    /**
     * Metodo que obtiene el ID del autor del comentario. 0 = para usuarios no registrados.
     * @return int
     */
    public function getComment_user_ID() {
        return $this->comment_user_ID;
    }

    /**
     * Metodo que obtiene el ID de la entrada/post.
     * @return int
     */
    public function getPost_ID() {
        return $this->post_ID;
    }

}
