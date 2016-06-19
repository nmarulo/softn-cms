<?php
/**
 * Controlador de la pagina de usuarios.
 */
require 'sn-admin.php';
SN_Users::checkRol('admin', true);

if (filter_input(INPUT_POST, 'action') == 'delete' && filter_input(INPUT_POST, 'id')) {
    if (SN_Users::getSession()->getID() != filter_input(INPUT_POST, 'id') && SN_Users::delete(filter_input(INPUT_POST, 'id'))) {
        Messages::add('Usuario borrado.', Messages::TYPE_S);
    } else {
        Messages::add('Error al borrar el usuario.', Messages::TYPE_E);
    }
}
/**
 * Metodo que imprime una tabla con los datos de los usuarios.
 * @global array $dataTable
 */
function reloadData() {
    global $dataTable;

    $str = '';
    $page = filter_input(INPUT_POST, 'paged') ? $_POST['paged'] : 1;
    $arg = pagedNavArg([
        'pagedNow' => $page,
        'countData' => count($dataTable['user']['dataList'])
    ]);
    
    for ($i = $arg['beginRow']; $i < $arg['endRow']; ++$i) {
        $user = $dataTable['user']['dataList'][$i];
        $user['user_rol'] = getRolName($user['user_rol']);
        $postNum = SN_Posts::user_posts_count($user['ID']);

        $str .= "<tr><td class='options'>";
        $str .= "<a class='btnAction-sm btn btn-primary' href='user-new.php?action=edit&id=$user[ID]' title='Editar'>";
        $str .= "<span class='glyphicon glyphicon-edit'></span></a> ";
        $str .= "<button class='btnAction btnAction-sm btn btn-danger' data-action='action=delete&id=$user[ID]' title='Borrar'><span class='glyphicon glyphicon-remove-sign'></span></button></td>";
        $str .= "<td><a href='" . siteUrl() . "?author=$user[ID]'>$user[user_login]</a></td>";
        $str .= "<td>$user[user_name]</td>";
        $str .= "<td>$user[user_email]</td>";
        $str .= "<td>$user[user_rol]</td>";
        $str .= "<td>$user[user_registred]</td>";
        $str .= "<td><span class='badge'>$postNum</span></td></tr>";
    }
    pagedNav($arg);
    ?>
    <div id="contentPost" class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th></th>
                    <th>Usuario</th>
                    <th>Nombre</th>
                    <th>E-mail</th>
                    <th>Rol</th>
                    <th>Registro</th>
                    <th>Entradas</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th></th>
                    <th>Usuario</th>
                    <th>Nombre</th>
                    <th>E-mail</th>
                    <th>Rol</th>
                    <th>Registro</th>
                    <th>Entradas</th>
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
    require ABSPATH . ADM_CONT . 'users.php';
}