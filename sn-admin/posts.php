<?php
/**
 * Controlador de la pagina de publicaciones.
 */
require 'sn-admin.php';
SN_Users::checkRol('editor', true);

if (filter_input(INPUT_POST, 'action') == 'delete' && filter_input(INPUT_POST, 'id')) {
    if (SN_Users::checkRol('admin', true)) {
        if (SN_Posts::delete(filter_input(INPUT_POST, 'id'), 'post')) {
            Messages::add('Entrada borrada.', Messages::TYPE_S);
        } else {
            Messages::add('Error al borrar la entrada.', Messages::TYPE_E);
        }
    }
}

/**
 * Metodo que imprime una tabla con los datos de las publicaciones.
 * @global array $dataTable
 */
function reloadData() {
    global $dataTable;
    
    $posts = SN_Users::checkRol('author') ? $dataTable['post']['dataList'] : SN_Posts::dataListByAuthor(SN_Users::getSession()->getID(), 'post');

    $str = '';
    $page = filter_input(INPUT_POST, 'paged') ? $_POST['paged'] : 1;
    $arg = pagedNavArg([
        'pagedNow' => $page,
        'countData' => count($posts)
    ]);

    for ($i = $arg['beginRow']; $i < $arg['endRow']; ++$i) {
        $post = $posts[$i];
        $author = SN_Users::get_instance($post['users_ID']);
        $post_status = $post['post_status'] ? 'Publicado' : 'Borrador';

        $title = $post['post_title'];

        if (isset($title{30})) {
            $title = substr($title, 0, 30) . ' [...]';
        }

        $str .= "<tr><td class='options'>";
        if (SN_Users::checkRol('editor')) {
            $str .= "<a class='label label-primary' href='post-new.php?action=edit&id=$post[ID]' title='Editar'>";
            $str .= "<span class='glyphicon glyphicon-edit'></span></a> ";
            if (SN_Users::checkRol()) {
                $str .= "<span class='spanAction label label-danger' data-action='action=delete&id=$post[ID]' title='Borrar'><span class='glyphicon glyphicon-remove-sign'></span></span>";
            }
        }
        $str .= "</td><td><a href='" . siteUrl() . "?post=$post[ID]' title='$post[post_title]'>$title</a></td>";
        $str .= "<td><a href='" . siteUrl() . "?author=$post[users_ID]'>" . $author->getUser_name() . "</a></td>";
        $str .= "<td><span class='badge'>$post[comment_count]</span></td>";
        $str .= "<td>$post[post_date]</td>";
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
    require ABSPATH . ADM_CONT . 'posts.php';
}