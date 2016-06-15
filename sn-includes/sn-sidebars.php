<?php

/**
 * Gestión de los sidebars.
 * @package SoftN-CMS\sn-includes
 */

/**
 * Clase para implementar los sidebars como objetos.
 * @author Nicolás Marulanda P.
 */
class SN_Sidebars {

    /** @var int Identificador del sidebar. */
    private $ID;

    /** @var string Titulo. */
    private $sidebar_title;

    /** @var string Contenido. */
    private $sidebar_contents;

    /** @var int Posición. */
    private $sidebar_position;

    /**
     * Constructor.
     * @param array|PDOStatement $arg Datos del sidebar.<br/>
     * <b>NOTA: Los indices del array deben corresponder con el nombre de la tabla.</b>
     */
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

    /**
     * Metodo que obtiene una lista con los sidebars que contienen 
     * el texto pasado por parametro.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param string $str
     * @return array
     */
    public static function search($str) {
        global $sndb;

        return $sndb->query([
                    'table' => 'sidebars',
                    'where' => 'sidebar_title LIKE :sidebar_title',
                    'prepare' => [[':sidebar_title', '%' . $str . '%'],]
                        ], 'fetchAll');
    }

    /**
     * Metodo que borra un sidebar segun su id.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @global array $dataTable Lista de datos de uso común.
     * @param int $id Identificador de la categoría.
     * @return bool
     */
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

    /**
     * Metodo que obtiene todos los sidebars ordenados por posición.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param string $fetch [Opcional] Tipo de datos a retornar.
     * Con "fetchObject" para retornar los datos como objetos. 
     * Por defecto, "fetchAll", retorna un array asociativo.
     * @return array|object
     */
    public static function dataList($fetch = 'fetchAll') {
        global $sndb;

        return $sndb->query(array('table' => 'sidebars', 'orderBy' => 'sidebar_position'), $fetch);
    }

    /**
     * Metodo que obtiene un sidebar segun su ID y retorna 
     * un instancia SN_Sidebars con los datos.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param int $id Identificador del sidebar.
     * @return object
     */
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

    /**
     * Metodo que obtiene el ultimo sidebar.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @return object Retorna un objeto PDOstatement.
     */
    public static function get_lastInsert() {
        global $sndb;

        return $sndb->query(array(
                    'table' => 'sidebars',
                    'orderBy' => 'ID DESC',
                    'limit' => '1'
                        ), 'fetchObject');
    }

    /**
     * Metodo que agrega los datos del sidebar a la base de datos.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @global array $dataTable Lista de datos de uso común.
     * @return bool
     */
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
                $out = true;
            }
        }

        return $out;
    }

    /**
     * Metodo que actualiza los datos del sidebar.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @global array $dataTable Lista de datos de uso común.
     * @return bool
     */
    public function update() {
        global $sndb, $dataTable;

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

        if ($out) {
            $dataTable['sidebar']['dataList'] = SN_Sidebars::dataList();
        }

        return $out;
    }

    /**
     * Metodo que obtiene el identificador del sidebar.
     * @return int
     */
    public function getID() {
        return $this->ID;
    }

    /**
     * Metodo que obtiene el titulo.
     * @return string
     */
    public function getSidebar_title() {
        return $this->sidebar_title;
    }

    /**
     * Metodo que obtiene el contenido.
     * @return string
     */
    public function getSidebar_contents() {
        return $this->sidebar_contents;
    }

    /**
     * Metodo que obtiene la posición.
     * @return int
     */
    public function getSidebar_position() {
        return $this->sidebar_position;
    }

    /**
     * Metodo que asigna la posición del sidebar.
     * @param int $sidebar_position
     */
    public function setSidebar_position($sidebar_position) {
        $this->sidebar_position = $sidebar_position;
    }

    /**
     * Metodo que actualiza las posiciones de los sidebars.
     */
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
