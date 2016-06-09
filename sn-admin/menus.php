<?php
/**
 * Controlador de la pagina de menus.
 */
require 'sn-admin.php';
SN_Users::checkRol('author', true);
/*
 * Recoge los datos del menu. Para mostrar los datos en
 * sus campos correspontiendes del modelo vista.
 */
$menu = [
    'ID' => 0,
    'menu_name' => '',
    'menu_title' => '',
    'menu_url' => '',
    'menu_sub' => -1,
    'menu_position' => 1
];

//Lista de menus hijos.
$menuList = [];
//Lista de menus padres.
$menuParents = SN_Menus::dataList('fetchAll', true);

if (isset($_GET['selectMenu'])) {
    $dataTable['menu']['select'] = SN_Menus::get_instance($_GET['selectMenu'], true);
}

if (!empty($dataTable['menu']['select']) && empty($menuList)) {
    $menuList = SN_Menus::getChildrens($dataTable['menu']['select']->ID);
}
if (SN_Users::checkRol()) {
//Cambia de posición con el submenu anterior.
    if (isset($_POST['up'])) {
        //Submenu seleccionado.
        $auxMenu = SN_Menus::get_instance($_POST['up']);
        if ($auxMenu) {
            $auxMenuUP = SN_Menus::get_instanceByPosition($auxMenu->getMenu_sub(), $auxMenu->getMenu_position() - 1);
            //Se comprueba que existe un menu en la posicion a la que se desea cambiar
            if ($auxMenuUP) {
                //Se actualizan las posiciones.
                $auxMenu->setMenu_position($auxMenu->getMenu_position() - 1);
                $auxMenuUP->setMenu_position($auxMenuUP->getMenu_position() + 1);
                if (!$auxMenu->update()) {
                    Messages::add('Error al actualizar menu [ID=' . $auxMenu->getID() . ']', Messages::TYPE_E);
                }
                if (!$auxMenuUP->update()) {
                    Messages::add('Error al actualizar menu [ID=' . $auxMenuUP->getID() . ']', Messages::TYPE_E);
                }
            } else {
                Messages::add('No se puede subir de posición este menu.', Messages::TYPE_E);
            }
        } else {
            Messages::add("El menu con ID = $_POST[up] no existe.", Messages::TYPE_E);
        }
//Cambia de menu padre y se vuelve hijo del submenu anterior.
    } elseif (isset($_POST['upup'])) {
        //Submenu seleccionado.
        $auxMenu = SN_Menus::get_instance($_POST['upup']);
        if ($auxMenu) {
            $auxMenuUP = SN_Menus::get_instanceByPosition($auxMenu->getMenu_sub(), $auxMenu->getMenu_position() - 1);
            //Se comprueba que existe un menu en la posicion a la que se desea cambiar
            if ($auxMenuUP) {
                /*
                 * Se obtienen los submenus del menu padre del menu seleccionado
                 * sin incluir el menu seleccionado, esto es para luego reordenar
                 * las posiciones de los menus.
                 */
                $childrenParentAuxMenu = SN_Menus::getChildrens($auxMenu->getMenu_sub(), $auxMenu->getID());
                /*
                 * Se obtienen los submenus del menu anterior para asignarle una
                 * posición al submenu seleccionado.
                 */
                $childrenAuxMenuUp = SN_Menus::getChildrens($auxMenuUP->getID());
                //Se actualiza el menu padre del submenu seleccionado.
                $auxMenu->setMenu_sub($auxMenuUP->getID());
                //Se actualiza su posición.
                $auxMenu->setMenu_position(count($childrenAuxMenuUp) + 1);

                if (!$auxMenu->update()) {
                    Messages::add('Error al actualizar menu [ID=' . $auxMenu->getID() . ']', Messages::TYPE_E);
                }

                /*
                 * Ahora se reordenar las posiciones de los hijos del padre anterior
                 * del menu seleccionado. 
                 */
                $count = count($childrenParentAuxMenu);
                $error = 0;
                for ($i = 1; $i <= $count; ++$i) {
                    $auxMenu = $childrenParentAuxMenu[$i - 1];
                    if ($auxMenu['menu_position'] != $i) {
                        $auxMenu['menu_position'] = $i;
                        $auxMenu = new SN_Menus($auxMenu);
                        if (!$auxMenu->update()) {
                            ++$error;
                        }
                    }
                }

                if ($error) {
                    Messages::add('Error al actualizar las posiciones.', Messages::TYPE_E);
                }
            } else {
                Messages::add('No se puede subir de posición este menu.', Messages::TYPE_E);
            }
        } else {
            Messages::add("El menu con ID = $_POST[upup] no existe.", Messages::TYPE_E);
        }
//Cambia de posición con el submenu siguiente.
    } elseif (isset($_POST['down'])) {
        //Submenu seleccionado.
        $auxMenu = SN_Menus::get_instance($_POST['down']);
        if ($auxMenu) {
            $auxMenuDown = SN_Menus::get_instanceByPosition($auxMenu->getMenu_sub(), $auxMenu->getMenu_position() + 1);
            //Se comprueba que existe un menu en la posicion a la que se desea cambiar
            if ($auxMenuDown) {
                //Se actualizan las posiciones.
                $auxMenu->setMenu_position($auxMenu->getMenu_position() + 1);
                $auxMenuDown->setMenu_position($auxMenuDown->getMenu_position() - 1);
                if (!$auxMenu->update()) {
                    Messages::add('Error al actualizar menu [ID=' . $auxMenu->getID() . ']', Messages::TYPE_E);
                }
                if (!$auxMenuDown->update()) {
                    Messages::add('Error al actualizar menu [ID=' . $auxMenuDown->getID() . ']', Messages::TYPE_E);
                }
            } else {
                Messages::add('No se puede bajar de posición este menu.', Messages::TYPE_E);
            }
        } else {
            Messages::add("El menu con ID = $_POST[down] no existe.", Messages::TYPE_E);
        }
//Cambia de menu padre y se vuelve hijo del submenu siguiente.
    } elseif (isset($_POST['downdown'])) {
        //Submenu seleccionado
        $auxMenu = SN_Menus::get_instance($_POST['downdown']);
        if ($auxMenu) {
            $auxMenuDown = SN_Menus::get_instanceByPosition($auxMenu->getMenu_sub(), $auxMenu->getMenu_position() + 1);
            //Se comprueba que existe un menu en la posicion a la que se desea cambiar
            if ($auxMenuDown) {
                /*
                 * Se obtienen los submenus del menu padre del menu seleccionado
                 * sin incluir el menu seleccionado, esto es para luego reordenar
                 * las posiciones de los menus.
                 */
                $childrenParentAuxMenu = SN_Menus::getChildrens($auxMenu->getMenu_sub(), $auxMenu->getID());
                /*
                 * Se obtienen los submenus del menu siguiente para asignarle una
                 * posición al submenu seleccionado.
                 */
                $childrenAuxMenuDown = SN_Menus::getChildrens($auxMenuDown->getID());
                //Se actualiza el menu padre del submenu seleccionado.
                $auxMenu->setMenu_sub($auxMenuDown->getID());
                //Se actualiza su posición.
                $auxMenu->setMenu_position(count($childrenAuxMenuDown) + 1);

                if (!$auxMenu->update()) {
                    Messages::add('Error al actualizar menu [ID=' . $auxMenu->getID() . ']', Messages::TYPE_E);
                }
                /*
                 * Ahora se reordenar las posiciones de los hijos del padre anterior
                 * del menu seleccionado. 
                 */
                $count = count($childrenParentAuxMenu);
                $error = 0;
                for ($i = 1; $i <= $count; ++$i) {
                    $auxMenu = $childrenParentAuxMenu[$i - 1];
                    if ($auxMenu['menu_position'] != $i) {
                        $auxMenu['menu_position'] = $i;
                        $auxMenu = new SN_Menus($auxMenu);
                        if (!$auxMenu->update()) {
                            ++$error;
                        }
                    }
                }
                if ($error) {
                    Messages::add('Error al actualizar las posiciones.', Messages::TYPE_E);
                }
            } else {
                Messages::add('No se puede bajar de posición este menu.', Messages::TYPE_E);
            }
        } else {
            Messages::add("El menu con ID = $_POST[downdown] no existe.", Messages::TYPE_E);
        }
//Cambia de menu padre y se vuelve hijo del submenu anterior. Similar al bloque "UPUP"
    } elseif (isset($_POST['out'])) {
        //Submenu seleccionado.
        $auxMenu = SN_Menus::get_instance($_POST['out']);
        if ($auxMenu) {
            $parent = SN_Menus::get_instance($auxMenu->getMenu_sub());
            if ($parent) {
                $childrenParentAuxMenu = SN_Menus::getChildrens($auxMenu->getMenu_sub(), $auxMenu->getID());
                $childrenParentParent = SN_Menus::getChildrens($parent->getMenu_sub());

                $auxMenu->setMenu_sub($parent->getMenu_sub());
                $auxMenu->setMenu_position(count($childrenParentParent) + 1);
                if (!$auxMenu->update()) {
                    Messages::add('Error al actualizar menu [ID=' . $auxMenu->getID() . ']', Messages::TYPE_E);
                }

                $count = count($childrenParentAuxMenu);
                $error = 0;
                for ($i = 1; $i <= $count; ++$i) {
                    $auxMenu = $childrenParentAuxMenu[$i - 1];
                    if ($auxMenu['menu_position'] != $i) {
                        $auxMenu['menu_position'] = $i;
                        $auxMenu = new SN_Menus($auxMenu);
                        if (!$auxMenu->update()) {
                            ++$error;
                        }
                    }
                }

                if ($error) {
                    Messages::add('Error al actualizar las posiciones.', Messages::TYPE_E);
                }
            } else {
                Messages::add('No se puede cambiar el menu padre de este menu.', Messages::TYPE_E);
            }
        } else {
            Messages::add("El menu con ID = $_POST[out] no existe.", Messages::TYPE_E);
        }
    } elseif ((filter_input(INPUT_GET, 'action') == 'edit' && filter_input(INPUT_GET, 'id')) || filter_input(INPUT_POST, 'update')) {

        $auxMenu = SN_Menus::get_instance(filter_input(INPUT_GET, 'id'));

        //Con esto mantengo el menu padre seleccionado
        if ($auxMenu->getMenu_sub()) {
            $dataTable['menu']['select'] = SN_Menus::get_instance($auxMenu->getMenu_sub(), true);
            $menuList = SN_Menus::getChildrens($dataTable['menu']['select']->ID, $auxMenu->getID());
        } else {
            $menuList = SN_Menus::getChildrens(0, $auxMenu->getID());
            $dataTable['menu']['select'] = SN_Menus::get_instance($auxMenu->getID(), true);
        }

        if ($auxMenu) {
            if (filter_input(INPUT_POST, 'update')) {
//            $name = filter_input(INPUT_POST, 'menu_name');
                $title = filter_input(INPUT_POST, 'menu_title');
                $description = filter_input(INPUT_POST, 'menu_url');
                $submenu = filter_input(INPUT_POST, 'menu_sub') ? filter_input(INPUT_POST, 'menu_sub') : 0;

                if ($submenu != $auxMenu->getMenu_sub()) {
                    $newParent = SN_Menus::get_instance($submenu);
                    $childrens = SN_Menus::getChildrens($newParent->getMenu_sub(), $auxMenu->getID());
                    $childrenNewParent = SN_Menus::getChildrens($newParent->getID());

                    $count = count($childrens);
                    for ($i = 1; $i <= $count; ++$i) {
                        $previousMenu = $childrens[$i - 1];
                        if ($previousMenu['menu_position'] != $i) {
                            $previousMenu['menu_position'] = $i;
                            $previousMenu = new SN_Menus($previousMenu);
                            $previousMenu->update();
                        }
                    }

                    $position = count($childrenNewParent) + 1;
                } else {
                    $position = $auxMenu->getMenu_position();
                }

                $arg = array(
                    'ID' => $auxMenu->getID(),
                    'menu_name' => $title,
                    'menu_url' => $description,
                    'menu_sub' => $submenu,
                    'menu_title' => $title,
                    'menu_position' => $position
                );

                $auxMenu = new SN_Menus($arg);
                if ($auxMenu->update()) {
                    Messages::add('Menu actualizado. [ID=' . $auxMenu->getID() . ']', Messages::TYPE_S);
                } else {
                    Messages::add('Error al actualizar menu [ID=' . $auxMenu->getID() . ']', Messages::TYPE_E);
                }
            }

            $menu = array(
                'menu_title' => $auxMenu->getMenu_title(),
                'menu_name' => $auxMenu->getMenu_name(),
                'menu_url' => $auxMenu->getMenu_url(),
                'menu_sub' => $auxMenu->getMenu_sub(),
                'menu_position' => $auxMenu->getMenu_position()
            );
        } else {
            Messages::add('El menu no existe.', Messages::TYPE_E);
        }
    } elseif (filter_input(INPUT_POST, 'publish')) {
//    $name = filter_input(INPUT_POST, 'menu_name');
        $title = filter_input(INPUT_POST, 'menu_title');
        $description = filter_input(INPUT_POST, 'menu_url');
        $submenu = filter_input(INPUT_POST, 'menu_sub');

        $menu = array(
            'menu_name' => $title,
            'menu_url' => $description,
            'menu_sub' => $submenu,
            'menu_title' => $title,
            'menu_position' => count(SN_Menus::getChildrens($submenu)) + 1
        );

        $auxMenu = new SN_Menus($menu);
        if ($auxMenu->insert()) {
            Messages::add('Menu publicado.', Messages::TYPE_S);
        } else {
            Messages::add('Error al publicar menu.', Messages::TYPE_E);
        }
    } elseif (filter_input(INPUT_GET, 'action') == 'delete' && filter_input(INPUT_GET, 'id')) {
        if (SN_Menus::delete(filter_input(INPUT_GET, 'id'))) {
            Messages::add('Menu borrado.', Messages::TYPE_S);
        } else {
            Messages::add('Error al borrar el menu.', Messages::TYPE_E);
        }
    }
} elseif (filter_input(INPUT_GET, 'action') || !empty (filter_input(INPUT_POST, 'publish'))) {
    Messages::add('No tienes suficientes permisos para realizar esta acción.', Messages::TYPE_W);
    redirect('menus', ADM);
}

/**
 * Metodo que el contenido con la lista de menus disponibles.
 * @global array $dataTable
 * @global array $menuParents Lista de menus padres.
 */
function reloadData() {
    global $dataTable, $menuParents;
    ?>
    <div id="contentPost"><!-- #contentPost -->
        <form class="form-inline" method="get">
            <div class="form-group">
                <label>Menús padres:</label>
                <select class="form-control" name="selectMenu">
                    <?php
                    foreach ($menuParents as $menus) {
                        $selected = '';
                        if ($menus['ID'] == $dataTable['menu']['select']->ID) {
                            $selected = ' selected';
                        }
                        echo "<option value='$menus[ID]'$selected>$menus[menu_title]</option>";
                    }
                    ?>
                </select>
            </div>
            <button class="btn btn-default" type="submit">Selecionar</button>
        </form>
        <h2>
            Menú:
            <?php
            if (empty($dataTable['menu']['select'])) {
                echo '- - -';
            } else {
                $id = $dataTable['menu']['select']->ID;
                $btn = '';
                if (SN_Users::checkRol()) {
                    $btn = "<a class='label label-primary' href='?action=edit&id=$id' title='Editar'><span class='glyphicon glyphicon-edit'></span></a> ";
                    $btn .= "<a class='label label-danger' href='?action=delete&id=$id' title='Borrar'><span class='glyphicon glyphicon-remove-sign'></span></a> ";
                }
                echo $btn . $dataTable['menu']['select']->menu_title;
            }
            ?>
        </h2>
        <form role="form" method="post">
            <?php listMenu($dataTable['menu']['select']); ?>
        </form>
    </div><!-- #contentPost.table-responsive -->
    <?php
}

//isReloadData es enviado mediante ajax
if (isset($_POST['isReloadData'])) {
    reloadData();
} else {
    require ABSPATH . ADM_CONT . 'menus.php';
}