<div id="user" data-collapse="#user" class="sn-content col-sm-9 col-md-10"><!-- Informacion - Contenido -->
    <div id="snwrap"><!-- #snwarp -->
        <div id="header" class="clearfix">
            <br/>
            <h1>Usuarios <a href="<?php echo $data['siteUrl']; ?>admin/user/insert/" class="btn btn-default">Nuevo usuario</a></h1>
        </div>
        <div id="reloadData"><!-- #content -->
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
                        <?php
                        foreach ($data['users'] as $user) {
                            $output = '<tr>';
                            $output .= '<td class="options">';
                            $output .= '<a class="btnAction-sm btn btn-primary" href="' . $data['siteUrl'] . 'admin/user/update/' . $user->getID() . '" title="Editar"><span class="glyphicon glyphicon-edit"></span></a> ';
                            $output .= '<a class="btnAction-sm btn btn-danger" href="' . $data['siteUrl'] . 'admin/user/delete/' . $user->getID() . '" title="Editar"><span class="glyphicon glyphicon-remove-sign"></span></a> ';
//                            $output .= '<button class="btnAction btnAction-sm btn btn-danger" data-action="" title="Borrar"><span class="glyphicon glyphicon-remove-sign"></span></button></td>';
                            $output .= '</td>';
                            $output .= '<td>' . $user->getUserLogin() . '</td>';
                            $output .= '<td>' . $user->getUserName() . '</td>';
                            $output .= '<td>' . $user->getUserEmail() . '</td>';
                            $output .= '<td>' . $user->getUserRol() . '</td>';
                            $output .= '<td>' . $user->getUserRegistred() . '</td>';
                            $output .= '<td><span class="badge">' . $user->getCountPosts() . '</span></td>';
                            $output .= '</tr>';
                            echo $output;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div><!-- #content -->
    </div><!-- #snwarp -->
</div><!-- Fin - Informacion - Contenido -->