<?php

/**
 * Description of sn-comments
 *
 * @author marulo
 */
class SN_Comments {

    private $ID;
    private $comment_status;
    private $comment_autor;
    private $comment_author_email;
    private $comment_date;
    private $comment_contents;
    private $comment_user_ID;
    private $post_ID;

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
    
    public static function search($str){
        global $sndb;
        
        return $sndb->query([
            'table' => 'comments',
            'where' => 'comment_contents LIKE :comment_contents',
            'orderBy' => 'ID DESC',
            'prepare' => [[':comment_contents', '%' . $str . '%'],]
        ], 'fetchAll');
    }

    public static function delete($id) {
        global $sndb, $dataTable;

        $out = $sndb->exec([
                    'type' => 'DELETE',
                    'table' => 'comments',
                    'where' => 'ID = :ID',
                    'prepare' => [[':ID', $id],],
        ]);
        
        if($out){
            $dataTable['comment']['dataList'] = SN_Comments::dataList();
        }
        
        return $out;
    }

    public static function dataList($fetch = 'fetchAll') {
        global $sndb;

        return $sndb->query(array('table' => 'comments', 'orderBy' => 'ID DESC'), $fetch);
    }

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

    public static function get_lastInsert() {
        global $sndb;

        return $sndb->query(array(
                    'table' => 'comments',
                    'orderBy' => 'ID DESC',
                    'limit' => '1'
                        ), 'fetchObject');
    }

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

    public function getID() {
        return $this->ID;
    }

    public function getComment_status() {
        return $this->comment_status;
    }

    public function getComment_autor() {
        return $this->comment_autor;
    }

    public function getComment_author_email() {
        return $this->comment_author_email;
    }

    public function getComment_date() {
        return $this->comment_date;
    }

    public function getComment_contents() {
        return $this->comment_contents;
    }

    public function getComment_user_ID() {
        return $this->comment_user_ID;
    }

    public function getPost_ID() {
        return $this->post_ID;
    }

}
