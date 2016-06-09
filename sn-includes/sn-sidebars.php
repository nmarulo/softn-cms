<?php

/**
 * Description of sn-sidebars
 *
 * @author marulo
 */
class SN_Sidebars {

    private $ID;
    private $sidebar_title;
    private $sidebar_contents;
    private $sidebar_position;

    public function __construct($arg) {
        if (is_object($arg)) {
            $this->ID = $arg->ID;
            $this->sidebar_title = $arg->sidebar_title;
            $this->sidebar_contents = $arg->sidebar_contents;
            $this->sidebar_position = $arg->sidebar_position;
        } elseif (is_array($arg)) {
            $sidebars = SN_Sidebars::dataList();
            $posLast = 1;
            if (count($sidebars) > 0) {
                $posLast = $sidebars[count($sidebars) - 1]['sidebar_position'] + 1;
            }

            $default = array(
                'ID' => 0,
                'sidebar_title' => '',
                'sidebar_contents' => '',
                'sidebar_position' => $posLast,
            );

            $default = array_merge($default, $arg);

            $this->ID = $default['ID'];
            $this->sidebar_title = $default['sidebar_title'];
            $this->sidebar_contents = $default['sidebar_contents'];
            $this->sidebar_position = $default['sidebar_position'];
        } else {
            echo 'ERROR. Tipo de parametro incorrecto.';
        }
    }
    
    public static function search($str){
        global $sndb;
        
        return $sndb->query([
            'table' => 'sidebars',
            'where' => 'sidebar_title LIKE :sidebar_title',
            'prepare' => [[':sidebar_title', '%' . $str . '%'],]
        ], 'fetchAll');
    }

    public static function delete($id) {
        global $sndb, $dataTable;
        $out = $sndb->exec([
            'type' => 'DELETE',
            'table' => 'sidebars',
            'where' => 'ID = :ID',
            'prepare' => [[':ID', $id]],
        ]);

        if ($out) {
            self::updateAllPositions();
            $dataTable['sidebar']['dataList'] = SN_Sidebars::dataList();
        }

        return $out;
    }

    public static function dataList($fetch = 'fetchAll') {
        global $sndb;

        return $sndb->query(array('table' => 'sidebars', 'orderBy' => 'sidebar_position'), $fetch);
    }

    public static function get_instance($id) {
        global $sndb;

        $out = $sndb->query(array(
            'table' => 'sidebars',
            'where' => 'ID = :ID',
            'prepare' => [[':ID', $id]],
                ), 'fetchObject');

        if ($out) {
            $out = new SN_Sidebars($out);
        }

        return $out;
    }

    public static function get_lastInsert() {
        global $sndb;

        return $sndb->query(array(
                    'table' => 'sidebars',
                    'orderBy' => 'ID DESC',
                    'limit' => '1'
                        ), 'fetchObject');
    }

    public function insert() {
        global $sndb, $dataTable;

        $out = $sndb->exec([
            'type' => 'INSERT',
            'table' => 'sidebars',
            'column' => 'sidebar_title, sidebar_contents, sidebar_position',
            'values' => ':sidebar_title, :sidebar_contents, :sidebar_position',
            'prepare' => [
                [':sidebar_title', $this->sidebar_title],
                [':sidebar_contents', $this->sidebar_contents],
                [':sidebar_position', $this->sidebar_position],
            ],
        ]);

        if ($out) {
            $out = SN_Sidebars::get_lastInsert();
            if ($out) {
                $this->ID = $out->ID;
                $dataTable['sidebar']['dataList'] = SN_Sidebars::dataList();
            }
        }

        return $out;
    }

    public function update() {
        global $sndb,$dataTable;

        $out = $sndb->exec([
                    'type' => 'UPDATE',
                    'table' => 'sidebars',
                    'set' => 'sidebar_title = :sidebar_title, sidebar_contents = :sidebar_contents, sidebar_position = :sidebar_position',
                    'where' => 'ID = :ID',
                    'prepare' => [
                        [':ID', $this->ID],
                        [':sidebar_title', $this->sidebar_title],
                        [':sidebar_contents', $this->sidebar_contents],
                        [':sidebar_position', $this->sidebar_position],
                    ],
        ]);
        
        if($out){
            $dataTable['sidebar']['dataList'] = SN_Sidebars::dataList();
        }
        
        return $out;
    }

    public function getID() {
        return $this->ID;
    }

    public function getSidebar_title() {
        return $this->sidebar_title;
    }

    public function getSidebar_contents() {
        return $this->sidebar_contents;
    }

    public function getSidebar_position() {
        return $this->sidebar_position;
    }

    public function setSidebar_position($sidebar_position) {
        $this->sidebar_position = $sidebar_position;
    }

    public static function updateAllPositions() {
        $position = 1;
        $error = 0;

        /**
         * Array donde el indice corresponde a la posicion del sidebar
         * y su valor es el ID
         */
        $sidebarsID = array_column(SN_Sidebars::dataList(), 'ID', 'sidebar_position');

        /**
         * Compruebo cada sidebar verificando si su posición es correcta,
         * de no ser asi actualizo el sidebar
         */
        foreach ($sidebarsID as $sidebar_position => $ID) {
            if ($position != $sidebar_position) {
                $sidebar = self::get_instance($ID);
                $sidebar->setSidebar_position($position);
                if (!$sidebar->update()) {
                    ++$error;
                }
            }
            ++$position;
        }

        if ($error) {
            Messages::add('Error al actualizar las posiciones.', Messages::TYPE_E);
        }
    }

}
