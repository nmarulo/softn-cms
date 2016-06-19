<?php
/**
 * Controlador de la pagina de categorias.
 */
require 'sn-admin.php';
SN_Users::checkRol('author', true);

/*
 * Recoge los datos de la categoria. Para mostrar los datos en
 * sus campos correspontiendes del modelo vista.
 */
$category = [
    'ID' => 0,
    'category_name' => '',
    'category_description' => '',
];

//Cambia el texto los botones de publicar y actualizar
$action_edit = false;

if ((filter_input(INPUT_GET, 'action') == 'edit' && filter_input(INPUT_GET, 'id')) || filter_input(INPUT_POST, 'update')) {
    $action_edit = true;
    $auxCategory = SN_Categories::get_instance(filter_input(INPUT_GET, 'id') | filter_input(INPUT_POST, 'update'));

    if ($auxCategory) {
        if (filter_input(INPUT_POST, 'update')) {
            $title = filter_input(INPUT_POST, 'category_name');
            $description = filter_input(INPUT_POST, 'category_description');
            $arg = [
                'ID' => $auxCategory->getID(),
                'category_name' => $title,
                'category_description' => $description,
                'category_count' => $auxCategory->getCategory_count(),
            ];

            $auxCategory = new SN_Categories($arg);
            if ($auxCategory->update()) {
                Messages::add('Categoría actualizada.', Messages::TYPE_S);
            } else {
                Messages::add('Error al actualizar la categoría.', Messages::TYPE_E);
            }
        }

        $category = [
            'ID' => $auxCategory->getID(),
            'category_name' => $auxCategory->getCategory_name(),
            'category_description' => $auxCategory->getCategory_description(),
        ];
    } else {
        Messages::add('La categoría no existe.', Messages::TYPE_E);
    }
} elseif (filter_input(INPUT_POST, 'publish')) {
    $title = filter_input(INPUT_POST, 'category_name');
    $description = filter_input(INPUT_POST, 'category_description');
    $category = [
        'category_name' => $title,
        'category_description' => $description,
    ];

    $auxCategory = new SN_Categories($category);
    if ($auxCategory->insert()) {
        Messages::add('Categoría publicada.', Messages::TYPE_S);
    } else {
        Messages::add('Error al publicar categoría.', Messages::TYPE_E);
    }
} elseif (filter_input(INPUT_POST, 'action') == 'delete' && filter_input(INPUT_POST, 'id')) {
    if (SN_Users::checkRol('admin', true)) {
        if (SN_Categories::delete(filter_input(INPUT_POST, 'id'))) {
            Messages::add('Categoría borrada.', Messages::TYPE_S);
        } else {
            Messages::add('Error al borrar la categoría.', Messages::TYPE_E);
        }
    }
}

/**
 * Metodo que imprime una tabla con los datos de las categorías.
 * @global array $dataTable
 */
function reloadData() {
    global $dataTable;

    $str = '';
    $page = filter_input(INPUT_POST, 'paged') ? $_POST['paged'] : 1;
    $arg = pagedNavArg([
        'pagedNow' => $page,
        'countData' => count($dataTable['category']['dataList']),
    ]);

    for ($i = $arg['beginRow']; $i < $arg['endRow']; ++$i) {
        $category = $dataTable['category']['dataList'][$i];
        $str .= "<tr><td class='options'>";
        $str .= "<a class='btnAction-sm btn btn-primary' href='?action=edit&id=$category[ID]' title='Editar'>";
        $str .= "<span class='glyphicon glyphicon-edit'></span></a> ";
        $str .= "<button class='btnAction btnAction-sm btn btn-danger' data-action='action=delete&id=$category[ID]' title='Borrar'><span class='glyphicon glyphicon-remove-sign'></span></button>";
        $str .= "</td><td><a href='" . siteUrl() . "?category=$category[ID]' title='$category[category_name]'>$category[category_name]</a></td>";
        $str .= "<td><span class='badge'>$category[category_count]</span></td></tr>";
    }
    pagedNav($arg);
    ?>
    <div id="contentPost" class="table-responsive"><!-- #contentPost.table-responsive -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th></th>
                    <th>Nombre</th>
                    <th>Entradas</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th></th>
                    <th>Nombre</th>
                    <th>Entradas</th>
                </tr>
            </tfoot>
            <tbody>
                <?php echo $str; ?>
            </tbody>
        </table>
    </div><!-- #contentPost.table-responsive -->
    <?php
    pagedNav($arg);
}

//isReloadData es enviado mediante ajax
if (isset($_POST['isReloadData'])) {
    Messages::show();
    reloadData();
} else {
    require ABSPATH . ADM_CONT . 'categories.php';
}