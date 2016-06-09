<?php

/**
 * Description of sn_menus
 *
 * @author marulo
 */
class SN_Menus {

    private $ID;
    private $menu_name;
    private $menu_url;
    private $menu_sub;
    private $menu_position;
    private $menu_title;

    public function __construct($arg) {
        if (is_object($arg)) {
            $this->ID = $arg->ID;
            $this->menu_name = $arg->menu_name;
            $this->menu_url = $arg->menu_url;
            $this->menu_sub = $arg->menu_sub;
            $this->menu_position = $arg->menu_position;
            $this->menu_title = $arg->menu_title;
        } elseif (is_array($arg)) {
            $default = array(
                'ID' => 0,
                'menu_name' => '',
                'menu_url' => '',
                'menu_sub' => 0,
                'menu_position' => 1,
                'menu_title' => ''
            );

            $default = array_merge($default, $arg);

            $this->ID = $default['ID'];
            $this->menu_name = $default['menu_name'];
            $this->menu_url = $default['menu_url'];
            $this->menu_sub = $default['menu_sub'];
            $this->menu_position = $default['menu_position'];
            $this->menu_title = $default['menu_title'];
        } else {
            echo 'ERROR. Tipo de parametro incorrecto.';
        }
    }

    public static function search($str) {
        global $sndb;

        return $sndb->query([
                    'table' => 'menus',
                    'where' => 'menu_title LIKE :menu_title',
                    'prepare' => [[':menu_title', '%' . $str . '%'],]
                        ], 'fetchAll');
    }

    public static function delete($id, $checkPosition = true) {
        global $sndb, $dataTable;
        $error = 0;
        if ($checkPosition) {
            $menu = SN_Menus::get_instance($id);
            if ($menu) {
                $children = SN_Menus::getChildrens($menu->getMenu_sub(), $id);
                if (SN_Menus::deleteChildren($id)) {
                    Messages::add('Error al borrar los menus.', Messages::TYPE_E);
                } else {
                    $count = count($children);
                    for ($i = 1; $i <= $count; ++$i) {
                        $menu = $children[$i - 1];
                        if ($menu['menu_position'] != $i) {
                            $menu['menu_position'] = $i;
                            $menu = new SN_Menus($menu);
                            if (!$menu->update()) {
                                ++$error;
                            }
                        }
                    }

                    if ($error) {
                        Messages::add('Error al actualizar las posiciones de los menus.', Messages::TYPE_E);
                    }
                }
            }
        }
        
        $out = $sndb->exec([
            'type' => 'DELETE',
            'table' => 'menus',
            'where' => 'ID = :ID',
            'prepare' => [[':ID', $id]],
        ]);

        if ($out) {
            $dataTable['menu']['dataList'] = SN_Menus::dataList();
        }

        return $out;
    }

    private static function deleteChildren($id) {
        global $sndb;

        $error = 0;
        $children = SN_Menus::getChildrens($id);
        foreach ($children as $menu) {
            $auxChildren = SN_Menus::getChildrens($menu['ID']);
            if ($auxChildren) {
                if (SN_Menus::deleteChildren($menu['ID'])) {
                    ++$error;
                }
            }
            //No se comprueban las posiciones ya que se borraran todos los menus.
            if (!SN_Menus::delete($menu['ID'], false)) {
                ++$error;
            }
        }
        return $error;
    }

    public static function dataList($fetch = 'fetchAll', $parents = false, $exclude = 0) {
        global $sndb;

        $where = '';
        $prepare = [];
//        $where .= $exclude ? "ID != $exclude" : '';
//        $where .= $parents && $exclude ? ' AND menu_sub = 0' : '';
//        $where .= $parents && !$exclude ? 'menu_sub = 0' : '';

        if ($exclude) {
            $where = "ID != :ID";
            $prepare[] = [':ID', $exclude];

            $where .= $parents && $exclude ? ' AND menu_sub = 0' : '';
        } elseif ($parents && !$exclude) {
            $where = "menu_sub = 0";
        }

        return $sndb->query(['table' => 'menus', 'where' => $where, 'prepare' => $prepare], $fetch);
    }

    public static function get_instance($id, $fetch = false) {
        global $sndb;

        $out = $sndb->query(array(
            'table' => 'menus',
            'where' => 'ID = :ID',
            'prepare' => [[':ID', $id]],
                ), 'fetchObject');

        if ($out && !$fetch) {
            $out = new SN_Menus($out);
        }

        return $out;
    }

    public static function get_instanceByPosition($id, $position) {
        global $sndb;

        $out = $sndb->query([
            'table' => 'menus',
            'where' => 'menu_sub = :menu_sub AND menu_position = :menu_position',
            'prepare' => [
                [':menu_sub', $id],
                [':menu_position', $position],
            ],
                ], 'fetchObject');

        if ($out) {
            $out = new SN_Menus($out);
        }

        return $out;
    }

    public static function get_lastInsert() {
        global $sndb;

        return $sndb->query([
                    'table' => 'menus',
                    'orderBy' => 'ID DESC',
                    'limit' => '1',
                        ], 'fetchObject');
    }

    public static function getChildrens($id, $exclude = 0, $fetch = 'fetchAll') {
        global $sndb;

        $where = 'menu_sub = :menu_sub';
        $prepare = [[':menu_sub', $id],];
//        $where .= $exclude ? " AND ID != $exclude" : '';
        if ($exclude) {
            $where .= ' AND ID != :ID';
            $prepare[] = [':ID', $exclude];
        }

        return $sndb->query([
                    'table' => 'menus',
                    'where' => $where,
                    'prepare' => $prepare,
                    'orderBy' => 'menu_position'
                        ], $fetch);
    }

    public function insert() {
        global $sndb;

        return $sndb->exec([
                    'type' => 'INSERT',
                    'table' => 'menus',
                    'column' => 'menu_name, menu_url, menu_sub, menu_title, menu_position',
                    'values' => ':menu_name, :menu_url, :menu_sub, :menu_title, :menu_position',
                    'prepare' => [
                        [':menu_name', $this->menu_name],
                        [':menu_url', $this->menu_url],
                        [':menu_sub', $this->menu_sub],
                        [':menu_title', $this->menu_title],
                        [':menu_position', $this->menu_position],
                    ],
        ]);
    }

    public function update() {
        global $sndb;

        return $sndb->exec([
                    'type' => 'UPDATE',
                    'table' => 'menus',
                    'set' => 'menu_name = :menu_name, menu_url = :menu_url, menu_sub = :menu_sub, menu_position = :menu_position, menu_title = :menu_title',
                    'where' => 'ID = :ID',
                    'prepare' => [
                        [':ID', $this->ID],
                        [':menu_name', $this->menu_name],
                        [':menu_url', $this->menu_url],
                        [':menu_sub', $this->menu_sub],
                        [':menu_position', $this->menu_position],
                        [':menu_title', $this->menu_title],
                    ],
        ]);
    }

    public function getID() {
        return $this->ID;
    }

    public function getMenu_name() {
        return $this->menu_name;
    }

    public function getMenu_url() {
        return $this->menu_url;
    }

    public function getMenu_sub() {
        return $this->menu_sub;
    }

    public function getMenu_position() {
        return $this->menu_position;
    }

    public function getMenu_title() {
        return $this->menu_title;
    }

    public function setMenu_position($menu_position) {
        $this->menu_position = $menu_position;
    }

    public function setMenu_sub($menu_sub) {
        $this->menu_sub = $menu_sub;
    }

    private function checkPermalink() {
        
    }

}
