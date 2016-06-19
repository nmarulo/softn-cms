<?php
/**
 * Controlador de la pagina de comentarios.
 */
require 'sn-admin.php';
SN_Users::checkRol('editor', true);

if (filter_input(INPUT_POST, 'action') == 'delete' && filter_input(INPUT_POST, 'id')) {
    if (SN_Users::checkRol('admin', true)) {
        if (SN_Comments::delete(filter_input(INPUT_POST, 'id'))) {
            Messages::add('Comentario borrado.', Messages::TYPE_S);
        } else {
            Messages::add('Error al borrar el comentario.', Messages::TYPE_E);
        }
    }
}

/**
 * Metodo que imprime una tabla con los datos de los comentarios.
 * @global array $dataTable
 */
function reloadData() {
    global $dataTable;

    $str = '';
    $page = filter_input(INPUT_POST, 'paged') ? $_POST['paged'] : 1;
    $arg = pagedNavArg([
        'pagedNow' => $page,
        'countData' => count($dataTable['comment']['dataList'])
    ]);
    for ($i = $arg['beginRow']; $i < $arg['endRow']; ++$i) {
        $comment = $dataTable['comment']['dataList'][$i];
        $post = SN_Posts::get_instance($comment['post_ID']);
        $contents = $comment['comment_contents'];
        $postTitle = $post->getPost_title();
        $comment_status = $comment['comment_status'] ? 'Aprobado' : 'Pendiente';

        if (strlen($contents) > 80) {
            $contents = substr($contents, 0, 80) . ' [...]';
        }

        if (strlen($postTitle) > 20) {
            $postTitle = substr($postTitle, 0, 20) . ' [...]';
        }


        $str .= "<tr><td class='options'>";
        if (SN_Users::checkRol()) {
            $str .= "<a class='btnAction-sm btn btn-primary' href='comment-edit.php?action=edit&id=$comment[ID]' title='Editar'>";
            $str .= "<span class='glyphicon glyphicon-edit'></span></a> ";
            $str .= "<button class='btnAction btnAction-sm btn btn-danger' data-action='action=delete&id=$comment[ID]' title='Borrar'><span class='glyphicon glyphicon-remove-sign'></span></button>";
        }
        $str .= "</td><td>$comment[comment_autor]</td>";
        $str .= "<td>$contents</td>";
        $str .= "<td>$comment_status</td>";
        $str .= "<td><a href='" . siteUrl() . "?post=" . $post->getID() . "' title='" . $post->getPost_title() . "'>$postTitle</a></td>";
        $str .= "<td>$comment[comment_date]</td></tr>";
    }
    pagedNav($arg);
    ?>
    <div id="contentPost" class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th></th>
                    <th>Autor</th>
                    <th>Comentario</th>
                    <th>Estado</th>
                    <th>Entrada</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th></th>
                    <th>Autor</th>
                    <th>Comentario</th>
                    <th>Estado</th>
                    <th>Entrada</th>
                    <th>Fecha</th>
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
    require ABSPATH . ADM_CONT . 'comments.php';
}