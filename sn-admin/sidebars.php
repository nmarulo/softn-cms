<?php
/**
 * Controlador de la pagina de la barra lateral.
 */
require 'sn-admin.php';
SN_Users::checkRol('author', true);

$posFirst = 0; //Posición del primer sidebar.
$posLast = 0; //Posición del ultimo sidebar.
if (count($dataTable['sidebar']['dataList']) > 0) {
    $posFirst = $dataTable['sidebar']['dataList'][0]['sidebar_position'];
    $posLast = $dataTable['sidebar']['dataList'][count($dataTable['sidebar']['dataList']) - 1]['sidebar_position'];
}

/*
 * Recoge los datos de la barra lateral. Para mostrar los datos en
 * sus campos correspontiendes del modelo vista.
 */
$sidebar = [
    'ID' => 0,
    'sidebar_title' => '',
    'sidebar_contents' => ''
];

if ((filter_input(INPUT_GET, 'action') == 'edit' && filter_input(INPUT_GET, 'id')) || filter_input(INPUT_POST, 'update')) {

    $auxSidebar = SN_Sidebars::get_instance(filter_input(INPUT_GET, 'id') | filter_input(INPUT_POST, 'update'));

    if ($auxSidebar) {
        if (filter_input(INPUT_POST, 'update')) {
            $title = filter_input(INPUT_POST, 'sidebar_title');
            $contents = filter_input(INPUT_POST, 'sidebar_contents');
            $arg = [
                'ID' => $auxSidebar->getID(),
                'sidebar_title' => $title,
                'sidebar_contents' => $contents,
                'sidebar_position' => $auxSidebar->getSidebar_position()
            ];
            $auxSidebar = new SN_Sidebars($arg);
            if ($auxSidebar->update()) {
                Messages::add('Contenido actualizado.', Messages::TYPE_S);
            } else {
                Messages::add('Error al actualizar.', Messages::TYPE_E);
            }
        }

        $sidebar = [
            'ID' => $auxSidebar->getID(),
            'sidebar_title' => $auxSidebar->getSidebar_title(),
            'sidebar_contents' => $auxSidebar->getSidebar_contents()
        ];
    } else {
        Messages::add('El id no existe.', Messages::TYPE_E);
    }
} elseif (filter_input(INPUT_POST, 'publish')) {
    $title = filter_input(INPUT_POST, 'sidebar_title');
    $contents = filter_input(INPUT_POST, 'sidebar_contents');

    $sidebar = [
        'sidebar_title' => $title,
        'sidebar_contents' => $contents
    ];

    $auxSidebar = new SN_Sidebars($sidebar);
    if ($auxSidebar->insert()) {
        Messages::add('Contenido publicado.', Messages::TYPE_S);
    } else {
        Messages::add('Error al publicar.', Messages::TYPE_E);
    }
//Cambia de posicion con el sidebar anterior.
} else if (filter_input(INPUT_POST, 'up')) {

    $auxSidebar = SN_Sidebars::get_instance(filter_input(INPUT_POST, 'up'));

    if ($auxSidebar) {
        $auxSidebarsList = array_column($dataTable['sidebar']['dataList'], 'sidebar_position', 'ID');

        if ($auxSidebar->getSidebar_position() > $posFirst) {
            $position = $auxSidebar->getSidebar_position() - 1;

            //Actualizo la posición del sidebar seleccionado
            $auxSidebar->setSidebar_position($position);
            $auxSidebar->update();

            /**
             * Retorna el valor del indice encontrado,
             * en este caso corresponde al ID del sidebar
             */
            $auxID = array_search($position, $auxSidebarsList);

            ++$position;

            /**
             * Obtengo y actualizo la posicion del sidebar
             * que esta en la misma posicion
             */
            $auxSidebar = SN_Sidebars::get_instance($auxID);
            $auxSidebar->setSidebar_position($position);
            if (!$auxSidebar->update()) {
                Messages::add('Error al actualizar. (UP)', Messages::TYPE_E);
            }
        }
    } else {
        Messages::add('La id no existe.', Messages::TYPE_E);
    }
//Cambia de posicion con el sidebar siguiente.
} elseif (filter_input(INPUT_POST, 'down')) {

    $auxSidebar = SN_Sidebars::get_instance(filter_input(INPUT_POST, 'down'));

    if ($auxSidebar) {
        $auxSidebarsList = array_column($dataTable['sidebar']['dataList'], 'sidebar_position', 'ID');

        if ($auxSidebar->getSidebar_position() < $posLast) {
            $position = $auxSidebar->getSidebar_position() + 1;

            $auxSidebar->setSidebar_position($position);
            $auxSidebar->update();

            $auxID = array_search($position, $auxSidebarsList);

            --$position;

            $auxSidebar = SN_Sidebars::get_instance($auxID);
            $auxSidebar->setSidebar_position($position);
            if (!$auxSidebar->update()) {
                Messages::add('Error al actualizar. (Down)', Messages::TYPE_E);
            }
        }
    } else {
        Messages::add('La id no existe.', Messages::TYPE_E);
    }
} elseif (filter_input(INPUT_POST, 'action') == 'delete' && filter_input(INPUT_POST, 'id')) {
    if (SN_Users::checkRol()) {
        if (SN_Sidebars::delete(filter_input(INPUT_POST, 'id'))) {
            Messages::add('Contenido borrado.', Messages::TYPE_S);
        } else {
            Messages::add('Error al borrar.', Messages::TYPE_E);
        }
    } else {
        Messages::add('No tienes suficientes permisos para realizar esta acción.', Messages::TYPE_W);
    }
}

/**
 * Metodo que imprime una tabla con los datos de las barras laterales.
 * @global arrya $dataTable
 * @global int $posFirst Posición del primer sidebar.
 * @global int $posLast Posición del ultimo sidebar.
 */
function reloadData() {
    global $dataTable, $posFirst, $posLast;
    ?>
    <div id="contentPost" class="table-responsive"><!-- #contentPost.table-responsive -->
        <form role="form" method="post">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Posición</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Nombre</th>
                        <th>Posición</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    foreach ($dataTable['sidebar']['dataList'] as $sidebar) {
                        $disabledUp = '';
                        $disabledDown = '';
                        if ($sidebar['sidebar_position'] == $posFirst) {
                            $disabledUp = ' disabled';
                        }
                        if ($sidebar['sidebar_position'] == $posLast) {
                            $disabledDown = ' disabled';
                        }
                        ?>
                        <tr>
                            <td>
                                <div class="panel panel-default">
                                    <div class='panel-heading'>
                                        <?php
                                        $btn = "<a class='label label-primary' href='?action=edit&id=$sidebar[ID]' title='Editar'><span class='glyphicon glyphicon-edit'></span></a> ";
                                        $btn .= "<span class='spanAction label label-danger' data-action='action=delete&id=$sidebar[ID]' title='Editar'><span class='glyphicon glyphicon-remove-sign'></span></span> ";
                                        echo $btn . $sidebar['sidebar_title'];
                                        ?>
                                    </div>
                                    <div class='panel-body'>
                                        <?php echo $sidebar['sidebar_contents']; ?>
                                    </div>
                                </div> 
                            </td>
                            <td>
                                <ul class="list-inline">
                                    <li>
                                        <span class="spanAction btn btn-success<?php echo $disabledUp; ?>" data-action="up=<?php echo $sidebar['ID']; ?>"><span class="glyphicon glyphicon-arrow-up"></span></span>
                                    </li>
                                    <li>
                                        <span class="badge"><?php echo $sidebar['sidebar_position']; ?></span>
                                    </li>
                                    <li>
                                        <span class="spanAction btn btn-danger<?php echo $disabledDown; ?>" data-action="down=<?php echo $sidebar['ID']; ?>"><span class="glyphicon glyphicon-arrow-down"></span></span>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </form>
    </div><!-- #contentPost.table-responsive -->
    <?php
}

//isReloadData es enviado mediante ajax
if (isset($_POST['isReloadData'])) {
    Messages::show();
    reloadData();
} else {
    require ABSPATH . ADM_CONT . 'sidebars.php';
}