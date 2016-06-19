<?php
/**
 * Controlador de la pagina de etiquetas.
 */
require 'sn-admin.php';
SN_Users::checkRol('author', true);

/*
 * Recoge los datos de la etiqueta. Para mostrar los datos en
 * sus campos correspontiendes del modelo vista.
 */
$term = [
    'ID' => 0,
    'term_name' => '',
    'term_description' => ''
];

//Cambia el texto los botones de publicar y actualizar
$action_edit = false;

if ((filter_input(INPUT_GET, 'action') == 'edit' && filter_input(INPUT_GET, 'id')) || filter_input(INPUT_POST, 'update')) {
    $action_edit = true;
    $auxTerm = SN_Terms::get_instance(filter_input(INPUT_GET, 'id') | filter_input(INPUT_POST, 'update'));

    if ($auxTerm) {
        if (filter_input(INPUT_POST, 'update')) {
            $title = filter_input(INPUT_POST, 'term_name');
            $description = filter_input(INPUT_POST, 'term_description');
            $arg = array(
                'ID' => $auxTerm->getID(),
                'term_name' => $title,
                'term_description' => $description,
                'term_count' => $auxTerm->getTerm_count()
            );

            $auxTerm = new SN_Terms($arg);
            if ($auxTerm->update()) {
                Messages::add('Etiqueta actualizada.', Messages::TYPE_S);
            } else {
                Messages::add('Error al actualizar la etiqueta.', Messages::TYPE_E);
            }
        }

        $term = [
            'ID' => $auxTerm->getID(),
            'term_name' => $auxTerm->getTerm_name(),
            'term_description' => $auxTerm->getTerm_description()
        ];
    } else {
        Messages::add('La etiqueta no existe.', Messages::TYPE_E);
    }
} elseif (filter_input(INPUT_POST, 'publish')) {
    $title = filter_input(INPUT_POST, 'term_name');
    $description = filter_input(INPUT_POST, 'term_description');
    $term = [
        'term_name' => $title,
        'term_description' => $description
    ];

    $auxTerm = new SN_Terms($term);
    if ($auxTerm->insert()) {
        Messages::add('Etiqueta publicada.', Messages::TYPE_S);
    } else {
        Messages::add('Error al publicar etiqueta.', Messages::TYPE_E);
    }
} elseif (filter_input(INPUT_POST, 'action') == 'delete' && filter_input(INPUT_POST, 'id')) {
    if (SN_Users::checkRol('admin', true)) {
        if (SN_Terms::delete(filter_input(INPUT_POST, 'id'))) {
            Messages::add('Etiqueta borrada.', Messages::TYPE_S);
        } else {
            Messages::add('Error al borrar la etiqueta.', Messages::TYPE_E);
        }
    }
}

/**
 * Metodo que imprime una tabla con los datos de las etiquetas.
 * @global array $dataTable
 */
function reloadData() {
    global $dataTable;

    $str = '';
    $page = filter_input(INPUT_POST, 'paged') ? $_POST['paged'] : 1;
    $arg = pagedNavArg([
        'pagedNow' => $page,
        'countData' => count($dataTable['term']['dataList'])
    ]);

    for ($i = $arg['beginRow']; $i < $arg['endRow']; ++$i) {
        $term = $dataTable['term']['dataList'][$i];

        $str .= "<tr><td class='options'>";
        $str .= "<a class='btnAction-sm btn btn-primary' href='?action=edit&id=$term[ID]' title='Editar'>";
        $str .= "<span class='glyphicon glyphicon-edit'></span></a> ";
        $str .= "<button class='btnAction btnAction-sm btn btn-danger' data-action='action=delete&id=$term[ID]' title='Borrar'><span class='glyphicon glyphicon-remove-sign'></span></button>";
        $str .= "</td><td><a href='" . siteUrl() . "?term=$term[ID]' title='$term[term_name]'>$term[term_name]</a></td>";
        $str .= "<td><span class='badge'>$term[term_count]</span></td></tr>";
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
    require ABSPATH . ADM_CONT . 'terms.php';
}