<div id="post" data-collapse="#post" class="sn-content col-sm-9 col-md-10"><!-- Informacion - Contenido -->
    <div id="snwrap"><!-- #snwarp -->
        <div id="header" class="clearfix">
            <br/>
            <h1>Publicaciones <a href="<?php $data['template']::getUrlPostInsert(); ?>" class="btn btn-default">Nueva entrada</a></h1>
        </div>
        <div id="reloadData">
            <?php $data['template']::getPagedNav(); ?>
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
                            $output .= '<a class="btnAction-sm btn btn-primary" href="' . $post->getUrlUpdate('', FALSE) . '" title="Editar"><span class="glyphicon glyphicon-edit"></span></a> ';
                            $output .= '<a class="btnAction-sm btn btn-danger" href="' . $post->getUrlDelete('', FALSE) . '" title="Editar"><span class="glyphicon glyphicon-remove-sign"></span></a> ';
//                            $output .= '<button class="btnAction btnAction-sm btn btn-danger" data-action="" title="Borrar"><span class="glyphicon glyphicon-remove-sign"></span></button>';
                            $output .= '</td>';
                            $output .= '<td><a href="'. $post->getUrl('', FALSE) .'" target="_blank">' . $post->getPostTitle() . '</a></td>';
                            $output .= '<td><a href="'.$post->getUrlUser('', FALSE) .'" target="_blank">' . $post->getUserName(FALSE) . '</a></td>';
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
            <?php $data['template']::getPagedNav(); ?>
        </div><!-- #content -->
    </div><!-- #snwarp -->
</div><!-- Fin - Informacion - Contenido -->
