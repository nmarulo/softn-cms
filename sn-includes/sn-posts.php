<?php

/**
 * Description of sn-posts
 *
 * @author marulo
 */
class SN_Posts {

    private $ID;
    private $post_title;
    private $post_status;
    private $post_date;
    private $post_update;
    private $post_contents;
    private $comment_status;
    private $comment_count;
    private $users_ID;
    private $post_type;

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

    public static function delete($id, $post_type = 0) {
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

    public static function dataListComments($id) {
        global $sndb;

        return $sndb->query([
                    'table' => 'comments',
                    'where' => 'post_ID = :post_ID',
                    'prepare' => [[':post_ID', $id]],
                    'orderBy' => 'ID DESC'
                        ], 'fetchAll');
    }

    public static function get_instance($id, $type = 'object') {
        global $sndb;

        $out = $sndb->query(array(
            'table' => 'posts',
            'where' => 'ID = :id',
            'prepare' => [[':id', $id]]
        ));

        if ($out) {
            switch ($type) {
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

    public static function get_lastInsert() {
        global $sndb;

        return $sndb->query(array(
                    'table' => 'posts',
                    'orderBy' => 'ID DESC',
                    'limit' => '1'
                        ), 'fetchObject');
    }

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
            }
        }

        return $out;
    }

    public function update() {
        global $sndb;

        return $sndb->exec(array(
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
        ));
    }

    public function getID() {
        return $this->ID;
    }

    public function getPost_title() {
        return $this->post_title;
    }

    public function getPost_status() {
        return $this->post_status;
    }

    public function getPost_date() {
        return $this->post_date;
    }

    public function getPost_update() {
        return $this->post_update;
    }

    public function getPost_contents() {
        return $this->post_contents;
    }

    public function getComment_status() {
        return $this->comment_status;
    }

    public function getComment_count() {
        return $this->comment_count;
    }

    public function getUsers_ID() {
        return $this->users_ID;
    }

    public function getPost_type() {
        return $this->post_type;
    }

}
