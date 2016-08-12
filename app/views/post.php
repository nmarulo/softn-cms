<div id="post" data-collapse="#post" class="sn-content col-sm-9 col-md-10"><!-- Informacion - Contenido -->
    <div id="snwrap"><!-- #snwarp -->
        <div id="header" class="clearfix">
            <br/>
            <h1>Publicaciones <a href="<?php echo $data['siteUrl']; ?>admin/post/insert" class="btn btn-default">Nueva entrada</a></h1>
        </div>
        <div id="reloadData">
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
                        <?php
                        foreach ($data['posts'] as $post) {
                            $output = '<tr>';
                            $output .= '<td class="options">';
                            $output .= '<a class="btnAction-sm btn btn-primary" href="' . $data['siteUrl'] . 'admin/post/update/' . $post->getID() . '" title="Editar"><span class="glyphicon glyphicon-edit"></span></a> ';
                            $output .= '<a class="btnAction-sm btn btn-danger" href="' . $data['siteUrl'] . 'admin/post/delete/' . $post->getID() . '" title="Editar"><span class="glyphicon glyphicon-remove-sign"></span></a> ';
//                            $output .= '<button class="btnAction btnAction-sm btn btn-danger" data-action="" title="Borrar"><span class="glyphicon glyphicon-remove-sign"></span></button>';
                            $output .= '</td>';
                            $output .= '<td>' . $post->getPostTitle() . '</td>';
                            $output .= '<td>' . $post->getUser()->getUserName() . '</td>';
                            $output .= '<td><span class="badge">' . $post->getCommentCount() . '</span></td>';
                            $output .= '<td>' . $post->getPostDate() . '</td>';
                            $output .= '<td>' . $post->getPostStatus() . '</td>';
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