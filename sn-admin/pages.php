<?php
/**
 * Controlador de la pagina de paginas.
 */
require 'sn-admin.php';
SN_Users::checkRol('author', true);

if (filter_input(INPUT_POST, 'action') == 'delete' && filter_input(INPUT_POST, 'id')) {
    if (SN_Users::checkRol('admin', true)) {
        if (SN_Posts::delete(filter_input(INPUT_POST, 'id'), 'page')) {
            Messages::add('Pagina borrada.', Messages::TYPE_S);
        } else {
            Messages::add('Error al borrar la pagina.', Messages::TYPE_E);
        }
    }
}

/**
 * Metodo que imprime una tabla con los datos de las paginas.
 * @global array $dataTable
 */
function reloadData() {
    global $dataTable;
    
    $pages = $dataTable['page']['dataList'];
    $str = '';
    $page = filter_input(INPUT_POST, 'paged') ? $_POST['paged'] : 1;
    $arg = pagedNavArg([
        'pagedNow' => $page,
        'countData' => count($pages)
    ]);

    for ($i = $arg['beginRow']; $i < $arg['endRow']; ++$i) {
        $page = $pages[$i];
        $author = SN_Users::get_instance($page['users_ID']);
        $title = $page['post_title'];
        $post_status = $page['post_status'] ? 'Publicado' : 'Borrador';

        if (isset($title{30})) {
            $title = substr($title, 0, 30) . ' [...]';
        }

        $str .= "<tr><td class='options'>";
            $str .= "<a class='btnAction-sm btn btn-primary' href='page-new.php?action=edit&id=$page[ID]' title='Editar'>";
            $str .= "<span class='glyphicon glyphicon-edit'></span></a> ";
        if (SN_Users::checkRol()) {
            $str .= "<button class='btnAction btnAction-sm btn btn-danger' data-action='action=delete&id=$page[ID]' title='Borrar'><span class='glyphicon glyphicon-remove-sign'></span></button>";
        }
        $str .= "</td><td><a href='" . siteUrl() . "?page=$page[ID]' title='$page[post_title]'>$title</a></td>";
        $str .= "<td><a href='" . siteUrl() . "?author=$page[users_ID]'>" . $author->getUser_name() . "</a></td>";
        $str .= "<td><span class='badge'>$page[comment_count]</span></td>";
        $str .= "<td>$page[post_date]</td>";
        $str .= "<td>$post_status</td></tr>";
    }

    pagedNav($arg);
    ?>
    <div id="contentPost" class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th></th>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Comentarios</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th></th>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Comentarios</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                </tr>
            </tfoot>
            <tbody>
                <?php echo $str; ?>
            </tbody>
        </table>
    </div>
    <?php
    pagedNav($arg);
}

//isReloadData es enviado mediante ajax
if (isset($_POST['isReloadData'])) {
    Messages::show();
    reloadData();
} else {
    require ABSPATH . ADM_CONT . 'pages.php';
}