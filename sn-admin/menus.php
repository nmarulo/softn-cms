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
$error = FALSE;
//Cambia el texto de los botones de publicar y actualizar
$action_edit = false;

//Lista de menus hijos.
$menuList = [];
//Lista de menus padres.
$menuParents = SN_Menus::dataList('fetchAll', true);

$selectMenu = filter_input(INPUT_GET, 'selectMenu') | filter_input(INPUT_POST, 'selectMenu');

if (empty($selectMenu) && !empty($menuParents)) {
    $selectMenu = SN_Menus::get_instance($menuParents[0]['ID']);
} else if (!empty($selectMenu)) {
    $selectMenu = SN_Menus::get_instance($selectMenu);
}

//Solo podre seleccionar menus que no sean hijos.
if ($selectMenu && $selectMenu->getMenu_sub() == 0) {
    $menuList = SN_Menus::getChildrens($selectMenu->getID());
} else if (!filter_input(INPUT_POST, 'publish')) {
    $error = true;
}



if (!$error) {
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
                    if (!$auxMenu->update() || !$auxMenuUP->update()) {
                        Messages::add('Error al actualizar los menus', Messages::TYPE_E);
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

                    if ($auxMenu->update()) {
                        /*
                         * Ahora se reordenar las posiciones de los hijos del padre anterior
                         * del menu seleccionado. 
                         */
                        $count = count($childrenParentAuxMenu);
                        $error = false;
                        for ($i = 1; $i <= $count && !$error; ++$i) {
                            $auxMenu = $childrenParentAuxMenu[$i - 1];
                            if ($auxMenu['menu_position'] != $i) {
                                $auxMenu['menu_position'] = $i;
                                $auxMenu = new SN_Menus($auxMenu);
                                $error = !$auxMenu->update();
                            }
                        }

                        if ($error) {
                            Messages::add('Error al actualizar las posiciones.', Messages::TYPE_E);
                        }
                    } else {
                        Messages::add('Error al actualizar los menus', Messages::TYPE_E);
                    }
                } else {
                    Messages::add('No se puede subir de posición este menu.', Messages::TYPE_E);
                }
            } else {
                Messages::add("El menu no existe.", Messages::TYPE_E);
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
                    if (!$auxMenu->update() || !$auxMenuDown->update()) {
                        Messages::add('Error al actualizar los menus', Messages::TYPE_E);
                    }
                } else {
                    Messages::add('No se puede bajar de posición este menu.', Messages::TYPE_E);
                }
            } else {
                Messages::add("El menu no existe.", Messages::TYPE_E);
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

                    if ($auxMenu->update()) {
                        /*
                         * Ahora se reordenar las posiciones de los hijos del padre anterior
                         * del menu seleccionado. 
                         */
                        $count = count($childrenParentAuxMenu);
                        $error = false;
                        for ($i = 1; $i <= $count && !$error; ++$i) {
                            $auxMenu = $childrenParentAuxMenu[$i - 1];
                            if ($auxMenu['menu_position'] != $i) {
                                $auxMenu['menu_position'] = $i;
                                $auxMenu = new SN_Menus($auxMenu);
                                $error = !$auxMenu->update();
                            }
                        }
                        if ($error) {
                            Messages::add('Error al actualizar las posiciones.', Messages::TYPE_E);
                        }
                    } else {
                        Messages::add('Error al actualizar el menu', Messages::TYPE_E);
                    }
                } else {
                    Messages::add('No se puede bajar de posición este menu.', Messages::TYPE_E);
                }
            } else {
                Messages::add("El menu no existe.", Messages::TYPE_E);
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
                    if ($auxMenu->update()) {
                        $count = count($childrenParentAuxMenu);
                        $error = false;
                        for ($i = 1; $i <= $count && !$error; ++$i) {
                            $auxMenu = $childrenParentAuxMenu[$i - 1];
                            if ($auxMenu['menu_position'] != $i) {
                                $auxMenu['menu_position'] = $i;
                                $auxMenu = new SN_Menus($auxMenu);
                                $error = !$auxMenu->update();
                            }
                        }

                        if ($error) {
                            Messages::add('Error al actualizar las posiciones.', Messages::TYPE_E);
                        }
                    } else {
                        Messages::add('Error al actualizar el menu', Messages::TYPE_E);
                    }
                } else {
                    Messages::add('No se puede cambiar el menu padre de este menu.', Messages::TYPE_E);
                }
            } else {
                Messages::add("El menu no existe.", Messages::TYPE_E);
            }
        } elseif ((filter_input(INPUT_GET, 'action') == 'edit' && filter_input(INPUT_GET, 'id')) || filter_input(INPUT_POST, 'update')) {
            $action_edit = true;
            $auxMenu = SN_Menus::get_instance(filter_input(INPUT_GET, 'id') | filter_input(INPUT_POST, 'update'));

            if ($auxMenu) {
                //Con esto mantengo el menu padre seleccionado
//                if ($auxMenu->getMenu_sub()) {
//                    $dataTable['menu']['select'] = SN_Menus::get_instance($auxMenu->getMenu_sub(), false);
//                    $menuList = SN_Menus::getChildrens($dataTable['menu']['select']->ID, $auxMenu->getID());
//                } else {
//                    $menuList = SN_Menus::getChildrens(0, $auxMenu->getID());
//                    $dataTable['menu']['select'] = SN_Menus::get_instance($auxMenu->getID(), false);
//                }
                if (filter_input(INPUT_POST, 'update')) {
                    $name = filter_input(INPUT_POST, 'menu_name');
                    $title = filter_input(INPUT_POST, 'menu_title');
                    $description = filter_input(INPUT_POST, 'menu_url');
                    $submenu = filter_input(INPUT_POST, 'menu_sub');
                    $error = false;
                    $isMenuParent = $auxMenu->getMenu_sub() == 0;

                    //Compruebo si el menu padre ha cambiado.
                    if ($submenu == $auxMenu->getMenu_sub()) {
                        $position = $auxMenu->getMenu_position();
                    } else {
                        if ($submenu) {
                            $newParent = SN_Menus::get_instance($submenu);
                            $childrens = SN_Menus::getChildrens($newParent->getMenu_sub(), $auxMenu->getID());
                            $childrenNewParent = SN_Menus::getChildrens($newParent->getID());

                            $count = count($childrens);
                            for ($i = 1; $i <= $count && !$error; ++$i) {
                                $previousMenu = $childrens[$i - 1];
                                if ($previousMenu['menu_position'] != $i) {
                                    $previousMenu['menu_position'] = $i;
                                    $previousMenu = new SN_Menus($previousMenu);
                                    $error = !$previousMenu->update();
                                }
                            }
                            $position = count($childrenNewParent) + 1;
                        } else {
                            $position = count(SN_Menus::dataList('fetchAll', true)) + 1;
                            $isMenuParent = true;
                        }
                    }

                    if ($error) {
                        Messages::add('Error al actualizar el menu.', Messages::TYPE_E);
                    } else {
                        $arg = [
                            'ID' => $auxMenu->getID(),
                            'menu_name' => str_filter($name),
                            'menu_url' => $description,
                            'menu_sub' => $submenu,
                            'menu_title' => $title,
                            'menu_position' => $position
                        ];

                        $auxMenu = new SN_Menus($arg);
                        if ($auxMenu->update()) {
                            Messages::add('Menu actualizado.', Messages::TYPE_S);
                            //Si era un menu padre o si se ahora es uno.
                            if ($isMenuParent) {
                                $menuParents = SN_Menus::dataList('fetchAll', true);
                                if ($selectMenu && $submenu == 0) {
                                    $selectMenu = $selectMenu->getID() == $auxMenu->getID() ? $auxMenu : $selectMenu;
                                } else {
                                    $selectMenu = SN_Menus::get_instance($menuParents[0]['ID']);
                                }
                                $menuList = SN_Menus::getChildrens($selectMenu->getID());
                            }
                        } else {
                            Messages::add('Error al actualizar el menu.', Messages::TYPE_E);
                        }
                    }
                }

                $menu = [
                    'ID' => $auxMenu->getID(),
                    'menu_title' => $auxMenu->getMenu_title(),
                    'menu_name' => $auxMenu->getMenu_name(),
                    'menu_url' => $auxMenu->getMenu_url(),
                    'menu_sub' => $auxMenu->getMenu_sub(),
                    'menu_position' => $auxMenu->getMenu_position()
                ];
            } else {
                Messages::add('El menu no existe.', Messages::TYPE_E);
            }
        } elseif (filter_input(INPUT_POST, 'publish')) {
            $title = filter_input(INPUT_POST, 'menu_title');
            $description = filter_input(INPUT_POST, 'menu_url');
            $submenu = filter_input(INPUT_POST, 'menu_sub');
            $menu_position = 0;
            $error = false;
            
            //El menu padre, si no es 0, debe existir.
            if ($submenu) {
                $error = empty(SN_Menus::get_instance($submenu));
            }else{
                $menu_position = count(SN_Menus::dataList('fetchAll', true)) + 1;
            }

            if (!$error) {
                $menu_position = $menu_position ? $menu_position : count(SN_Menus::getChildrens($submenu)) + 1;
                $menu = [
                    'menu_name' => str_filter($title),
                    'menu_url' => $description,
                    'menu_sub' => $submenu,
                    'menu_title' => $title,
                    'menu_position' => $menu_position,
                ];

                $auxMenu = new SN_Menus($menu);
                if ($auxMenu->insert()) {
                    Messages::add('Menu publicado.', Messages::TYPE_S);
                    //Si es un menu padre actualizo la lista.
                    if ($submenu == 0) {
                        $menuParents = SN_Menus::dataList('fetchAll', true);
                    }
                } else {
                    Messages::add('Error al publicar menu.', Messages::TYPE_E);
                }
            } else {
                Messages::add('El menu padre no existe.', Messages::TYPE_E);
            }
        } elseif (filter_input(INPUT_POST, 'action') == 'delete' && filter_input(INPUT_POST, 'id')) {
            $id = filter_input(INPUT_POST, 'id');
            if (SN_Menus::delete($id)) {
                Messages::add('Menu borrado.', Messages::TYPE_S);
                //Si he borrado el menu seleccionado, es decir un menu padre.
                if ($selectMenu->getID() == $id) {
                    $menuParents = SN_Menus::dataList('fetchAll', true);
                    $selectMenu = 0;
                }
            } else {
                Messages::add('Error al borrar el menu.', Messages::TYPE_E);
            }
        }
    } elseif (filter_input(INPUT_GET, 'action') || filter_input(INPUT_POST, 'publish')) {
        Messages::add('No tienes suficientes permisos para realizar esta acción.', Messages::TYPE_W);
        redirect('menus', ADM);
    }
} else if (empty($menuParents)) {
    Messages::add('No se ha creado ningun menu.', Messages::TYPE_W);
} else {
    Messages::add('El menu seleccionado no existe o no es valido.', Messages::TYPE_E);
}

/**
 * Metodo que el contenido con la lista de menus disponibles.
 * @global object $selectMenu Menu padre seleccionado.
 * @global array $menuParents Lista de menus padres.
 */
function reloadData() {
    global $selectMenu, $menuParents;
    ?>
    <div id="contentPost"><!-- #contentPost -->
        <form class="form-inline" method="get">
            <div class="form-group">
                <label>Menús padres:</label>
                <select class="form-control" name="selectMenu">
                    <?php
                    foreach ($menuParents as $parent) {
                        $selected = '';
                        if ($selectMenu && $parent['ID'] == $selectMenu->getID()) {
                            $selected = ' selected';
                        }
                        echo "<option value='$parent[ID]'$selected>$parent[menu_title]</option>";
                    }
                    ?>
                </select>
            </div>
            <button class="btn btn-default" type="submit">Selecionar</button>
        </form>
        <h2>
            Menú:
            <?php
            if ($selectMenu) {
                $id = $selectMenu->getID();
                $btn = '';
                if (SN_Users::checkRol()) {
                    $btn = "<a class='btnAction-sm btn btn-primary' href='?action=edit&id=$id&selectMenu=$id' title='Editar'><span class='glyphicon glyphicon-edit'></span></a> ";
                    $btn .= "<button class='btnAction btnAction-sm btn btn-danger' data-action='action=delete&id=$id&selectMenu=$id' title='Borrar'><span class='glyphicon glyphicon-remove-sign'></span></button> ";
                }
                echo $btn . $selectMenu->getMenu_title();
            } else {
                echo '- - -';
            }
            ?>
        </h2>
        <!--<form role="form" method="post">-->
        <?php SN_Menus::listMenu($selectMenu); ?>
        <!--</form>-->
    </div><!-- #contentPost.table-responsive -->
    <?php
}

//isReloadData es enviado mediante ajax
if (isset($_POST['isReloadData'])) {
    Messages::show();
    reloadData();
} else {
    require ABSPATH . ADM_CONT . 'menus.php';
}