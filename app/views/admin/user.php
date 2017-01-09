<div id="user" data-collapse="#user" class="sn-content col-sm-9 col-md-10"><!-- Informacion - Contenido -->
    <div id="snwrap"><!-- #snwarp -->
        <div id="header" class="clearfix">
            <br/>
            <h1>Usuarios <a href="<?php $data['template']::getUrlUserInsert(); ?>" class="btn btn-default">Nuevo usuario</a></h1>
        </div>
        <div id="content"><!-- #content -->
            <div id="reloadData"><!-- #reloadData -->
                <?php $data['template']::getPagedNav(); ?>
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
                                if ($data['template']::getUserLoginID() != $user->getID()) {
                                    $output .= '<a class="btnAction-sm btn btn-primary" href="' . $user->getUrlUpdate('', FALSE) . '" title="Editar"><span class="glyphicon glyphicon-edit"></span></a> ';
                                    $output .= '<a class="btnAction-sm btn btn-danger" href="' . $user->getUrlDelete('', FALSE) . '" title="Editar"><span class="glyphicon glyphicon-remove-sign"></span></a> ';
                                    //                            $output .= '<button class="btnAction btnAction-sm btn btn-danger" data-action="" title="Borrar"><span class="glyphicon glyphicon-remove-sign"></span></button></td>';
                                }
                                $output .= '</td>';
                                $output .= '<td>' . $user->getUserLogin() . '</td>';
                                $output .= '<td><a href="' . $user->getUrl('', FALSE) . '" target="_blank">' . $user->getUserName() . '</a></td>';
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
                <?php $data['template']::getPagedNav(); ?>
            </div>
        </div><!-- #content -->
    </div><!-- #snwarp -->
</div><!-- Fin - Informacion - Contenido -->
