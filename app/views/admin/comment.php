<div id="comment" class="sn-content col-sm-9 col-md-10"><!-- Informacion - Contenido -->
    <div id="snwrap"><!-- #snwarp -->
        <div id="header" class="clearfix">
            <br/>
            <h1>Comentarios</h1>
        </div>
        <div id="content"><!-- #content -->
            <div id="reloadData"><!-- #content -->
                <?php $data['template']::getPagedNav(); ?>
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
                            <?php
                            foreach ($data['comments'] as $comment) {
                                $output = '<tr>';
                                $output .= '<td class="options">';
                                $output .= '<a class="btnAction-sm btn btn-primary" href="' . $comment->getUrlUpdate('', FALSE) . '" title="Editar"><span class="glyphicon glyphicon-edit"></span></a> ';
                                $output .= '<a class="btnAction-sm btn btn-danger" href="' . $comment->getUrlDelete('', FALSE) . '" title="Editar"><span class="glyphicon glyphicon-remove-sign"></span></a> ';
                                //                            $output .= '<button class="btnAction btnAction-sm btn btn-danger" data-action="" title="Borrar"><span class="glyphicon glyphicon-remove-sign"></span></button>';
                                $output .= '</td>';
                                $output .= '<td>' . $comment->getCommentAuthor() . '</td>';
                                $output .= '<td><a href="' . $comment->getUrl('', FALSE) . '" target="_blank">' . $comment->getCommentContents() . '</a></td>';
                                $output .= '<td>' . $comment->getCommentStatus() . '</td>';
                                $output .= '<td>' . $comment->getPostID() . '</td>';
                                $output .= '<td>' . $comment->getCommentDate() . '</td>';
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
