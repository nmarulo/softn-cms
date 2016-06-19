<?php
/**
 * Gestión de los menus de navegación.
 * @package SoftN-CMS\sn-includes
 */

/**
 * Clase para implementar los menus como objetos.
 * @author Nicolás Marulanda P.
 */
class SN_Menus {

    /** @var int Identificador del menu. */
    private $ID;

    /** @var string Nombre del menu. */
    private $menu_name;

    /** @var string Dirección. */
    private $menu_url;

    /** @var int Identificador del menu padre. */
    private $menu_sub;

    /** @var int Posición. */
    private $menu_position;

    /** @var int Titulo. */
    private $menu_title;

    /**
     * Constructor.
     * @param array|PDOStatement $arg Datos del menu.<br/>
     * <b>NOTA: Los indices del array deben corresponder con el nombre de la tabla.</b>
     */
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

    /**
     * Metodo que obtiene una lista con los menus que contienen 
     * el texto pasado por parametro.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param string $str
     * @return array
     */
    public static function search($str) {
        global $sndb;

        return $sndb->query([
                    'table' => 'menus',
                    'where' => 'menu_title LIKE :menu_title',
                    'prepare' => [[':menu_title', '%' . $str . '%'],]
                        ], 'fetchAll');
    }

    /**
     * Metodo que borra un menu segun su ID.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @global array $dataTable Lista de datos de uso común.
     * @param int $id Identificador del menu.
     * @param bool $checkPosition [Opcional] Si es FALSE, se borra el menu 
     * sin realizar comprobaciones. Por defecto TRUE.
     * @return bool
     */
    public static function delete($id, $checkPosition = true) {
        global $sndb, $dataTable;
        $error = false;
        $out = false;

        if ($checkPosition) {
            $menu = self::get_instance($id);
            if ($menu) {
                $children = SN_Menus::getChildrens($menu->getMenu_sub(), $id);
                //Si deleteChildren retorna TRUE a ocurrido un error.
                if (SN_Menus::deleteChildren($id)) {
                    Messages::add('Error al borrar los menus.', Messages::TYPE_E);
                } else {
                    $count = count($children);
                    for ($i = 1; $i <= $count && !$error; ++$i) {
                        $menu = $children[$i - 1];
                        if ($menu['menu_position'] != $i) {
                            $menu['menu_position'] = $i;
                            $menu = new SN_Menus($menu);
                            if (!$menu->update()) {
                                $error = true;
                            }
                        }
                    }

                    if ($error) {
                        Messages::add('Error al actualizar las posiciones de los menus.', Messages::TYPE_E);
                    }
                }
            }
        }

        if (!$error) {
            $out = $sndb->exec([
                'type' => 'DELETE',
                'table' => 'menus',
                'where' => 'ID = :ID',
                'prepare' => [[':ID', $id]],
            ]);

            if ($out) {
                $dataTable['menu']['dataList'] = SN_Menus::dataList();
            }
        }

        return $out;
    }

    /**
     * Metodo que borra de forma recursiva los elementos hijos del menu.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param int $id Identificador del menu.
     * @return bool Retorna FALSE si la operación se completo correctamente.
     */
    private static function deleteChildren($id) {
        global $sndb;
        $error = false;
        $children = SN_Menus::getChildrens($id);
        $count = count($children);

        for ($i = 0; $i < $count && !$error; ++$i) {
            $auxChildren = SN_Menus::getChildrens($children[$i]['ID']);
            if ($auxChildren) {
                if (SN_Menus::deleteChildren($children[$i]['ID'])) {
                    $error = true;
                }
            }
            /*
             * En la funcion DELETE no se comprobaran las posiciones 
             * ya que se borraran todos los elementos del menu.
             */
            if (!$error && !SN_Menus::delete($children[$i]['ID'], false)) {
                $error = true;
            }
        }
        return $error;
    }

    /**
     * Metodo que obtiene todos los menus.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param string $fetch [Opcional] Tipo de datos a retornar.
     * Con "fetchObject" para retornar los datos como objetos. 
     * Por defecto, "fetchAll", retorna un array asociativo.
     * @param bool $parents [Opcional] Si es TRUE, retornara todos los menus 
     * que no sean hijos de ningun menu. Por defecto FALSE.
     * @param int $exclude [Opcional] Identificador del menu a excluir de la consulta.
     * @return array|object
     */
    public static function dataList($fetch = 'fetchAll', $parents = false, $exclude = 0) {
        global $sndb;
        $where = '';
        $prepare = [];

        if ($exclude) {
            $where = "ID != :ID";
            $prepare[] = [':ID', $exclude];
            $where .= $parents ? ' AND menu_sub = 0' : '';
        } elseif ($parents && !$exclude) {
            $where = "menu_sub = 0";
        }

        return $sndb->query(['table' => 'menus', 'where' => $where, 'prepare' => $prepare], $fetch);
    }

    /**
     * Metodo que obtiene un menu segun su ID y retorna 
     * un instancia SN_Menus con los datos.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param int $id
     * @param string $object [Opcional] Tipo de datos a retornar.
     * Si es false, retorna un objeto PDOstatement,
     * Por defecto, "TRUE", retorna un objecto SN_Menus.
     * @return object
     */
    public static function get_instance($id, $object = true) {
        global $sndb;

        $out = $sndb->query(array(
            'table' => 'menus',
            'where' => 'ID = :ID',
            'prepare' => [[':ID', $id]],
                ), 'fetchObject');

        if ($out && $object) {
            $out = new SN_Menus($out);
        }

        return $out;
    }

    /**
     * Metodo que obtiene un elemento del menu segun el ID de su menu padre y la
     * posicion del menu y retorna un instancia SN_Menus con los datos.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param int $menu_sub Identificador del menu padre.
     * @param int $position Posición del menu a obtener.
     * @return object
     */
    public static function get_instanceByPosition($menu_sub, $position) {
        global $sndb;

        $out = $sndb->query([
            'table' => 'menus',
            'where' => 'menu_sub = :menu_sub AND menu_position = :menu_position',
            'prepare' => [
                [':menu_sub', $menu_sub],
                [':menu_position', $position],
            ],
                ], 'fetchObject');

        if ($out) {
            $out = new SN_Menus($out);
        }

        return $out;
    }

    /**
     * Metodo que obtiene el ultimo menu.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @return object Retorna un objeto PDOstatement.
     */
    public static function get_lastInsert() {
        global $sndb;

        return $sndb->query([
                    'table' => 'menus',
                    'orderBy' => 'ID DESC',
                    'limit' => '1',
                        ], 'fetchObject');
    }

    /**
     * Metodo que obtiene los elementos hijos de un menu.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param int $id Identificador del menu padre.
     * @param int $exclude [Opcional] Identificador del menu a excluir de la consulta.
     * @param string $fetch [Opcional] Tipo de datos a retornar.
     * Con "fetchObject" para retornar los datos como objetos. 
     * Por defecto, "fetchAll", retorna un array asociativo.
     * @return array|object
     */
    public static function getChildrens($id, $exclude = 0, $fetch = 'fetchAll') {
        global $sndb;

        $where = 'menu_sub = :menu_sub';
        $prepare = [[':menu_sub', $id],];
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

    /**
     * Metodo que imprime los elementos del menu seleccionado. Ver menus.php
     * @param object $parent Datos del menu padre.
     * @param array $subChildrens Lista con los menus hijos.
     */
    public static function listMenu($parent, $subChildrens = 0) {

        if ($parent) {
            if (empty($subChildrens)) {
                $childrens = self::getChildrens($parent->getID());
            } else {
                $childrens = $subChildrens;
            }

            //Comprueba si tiene sub menus.
            if ($childrens) {
                $count = count($childrens);
                echo '<ul class="list-untyped">';
                foreach ($childrens as $menu) {
                    $disabled = [
                        'up' => ' disabled',
                        'upup' => ' disabled',
                        'down' => ' disabled',
                        'downdown' => ' disabled',
                        'out' => ' disabled',
                    ];
                    if ($menu['menu_position'] + 1 <= $count) {
                        $disabled['down'] = '';
                        $disabled['downdown'] = '';
                    }
                    if ($menu['menu_position'] - 1 >= 1) {
                        $disabled['up'] = '';
                        $disabled['upup'] = '';
                    }
                    if ($menu['menu_sub'] != $parent->getID()) {
                        $disabled['out'] = '';
                    }
                    ?>
                    <li>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="clearfix">
                                    <div class="pull-left">
                                        <?php
                                        $btn = '';
                                        if (SN_Users::checkRol()) {
                                            $btn = "<a class='btnAction-sm btn btn-primary' href='?action=edit&id=$menu[ID]&selectMenu=".$parent->getID()."' title='Editar'><span class='glyphicon glyphicon-edit'></span></a> ";
                                            $btn .= "<button class='btnAction btnAction-sm btn btn-danger' data-action='action=delete&id=$menu[ID]&selectMenu=".$parent->getID()."' title='Borrar'><span class='glyphicon glyphicon-remove-sign'></span></button> ";
                                        }
                                        echo $btn . $menu['menu_title'];
                                        ?>
                                    </div>
                                    <?php if (SN_Users::checkRol()) { ?>
                                        <div class="pull-right">
                                            <ul class="list-inline">
                                                <li>
                                                    <button class="btnAction btnAction-sm btn btn-success<?php echo $disabled['up']; ?>" data-action="up=<?php echo $menu['ID'] . '&selectMenu=' . $parent->getID(); ?>">
                                                        <span class="glyphicon glyphicon-arrow-up"></span>
                                                    </button>
                                                </li>
                                                <li>
                                                    <button class="btnAction btnAction-sm btn btn-danger<?php echo $disabled['down']; ?>" data-action="down=<?php echo $menu['ID'] . '&selectMenu=' . $parent->getID(); ?>">
                                                        <span class="glyphicon glyphicon-arrow-down"></span>
                                                    </button>
                                                </li>
                                                <li>
                                                    <span class="badge"><?php echo $menu['menu_position']; ?></span>
                                                </li>
                                                <li>
                                                    <button class="btnAction btnAction-sm btn btn-success<?php echo $disabled['upup']; ?>" data-action="upup=<?php echo $menu['ID'] . '&selectMenu=' . $parent->getID(); ?>">
                                                        <span class="glyphicon glyphicon-arrow-up"></span><span class="glyphicon glyphicon-arrow-up"></span>
                                                    </button>
                                                </li>
                                                <li>
                                                    <button class="btnAction btnAction-sm btn btn-danger<?php echo $disabled['downdown']; ?>" data-action="downdown=<?php echo $menu['ID'] . '&selectMenu=' . $parent->getID(); ?>">
                                                        <span class="glyphicon glyphicon-arrow-down"></span><span class="glyphicon glyphicon-arrow-down"></span>
                                                    </button>
                                                </li>
                                                <li>
                                                    <button class="btnAction btnAction-sm btn btn-warning<?php echo $disabled['out']; ?>" data-action="out=<?php echo $menu['ID'] . '&selectMenu=' . $parent->getID(); ?>">
                                                        <span class="glyphicon glyphicon-circle-arrow-up"></span>
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </li>
                    <?php
                    $subChildrens = self::getChildrens($menu['ID']);
                    if ($subChildrens) {
                        echo '<li>' . self::listMenu($parent, $subChildrens) . '</li>';
                    }
                }
                echo '</ul>';
            }
        }
    }

    /**
     * Metodo que agrega los datos del menu a la base de datos.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @global array $dataTable Lista de datos de uso común.
     * @return bool
     */
    public function insert() {
        global $sndb, $dataTable;

        $out = $sndb->exec([
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

        if ($out) {
            $dataTable['menu']['dataList'] = SN_Menus::dataList();
        }

        return $out;
    }

    /**
     * Metodo que actualiza los datos del menu.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @global array $dataTable Lista de datos de uso común.
     * @return bool
     */
    public function update() {
        global $sndb, $dataTable;

        $out = $sndb->exec([
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

        if ($out) {
            $dataTable['menu']['dataList'] = SN_Menus::dataList();
        }

        return $out;
    }

    /**
     * Metodo que obtiene el identificador del menu.
     * @return int
     */
    public function getID() {
        return $this->ID;
    }

    /**
     * Metodo que obtiene el nombre asignado.
     * @return string
     */
    public function getMenu_name() {
        return $this->menu_name;
    }

    /**
     * Metodo que obtiene la dirección del menu.
     * @return string
     */
    public function getMenu_url() {
        return $this->menu_url;
    }

    /**
     * Metodo que obtiene el identificador del menu padre.
     * @return int
     */
    public function getMenu_sub() {
        return $this->menu_sub;
    }

    /**
     * Metodo que obtiene la posición del menu.
     * @return int
     */
    public function getMenu_position() {
        return $this->menu_position;
    }

    /**
     * Metodo que obtiene el titulo del menu.
     * @return string
     */
    public function getMenu_title() {
        return $this->menu_title;
    }

    /**
     * Metodo que establece la posición del menu.
     * @param int $menu_position
     */
    public function setMenu_position($menu_position) {
        $this->menu_position = $menu_position;
    }

    /**
     * Metodo que establece el identificador del menu padre.
     * @param int $menu_sub
     */
    public function setMenu_sub($menu_sub) {
        $this->menu_sub = $menu_sub;
    }

//    private function checkPermalink() {
//        
//    }

}
