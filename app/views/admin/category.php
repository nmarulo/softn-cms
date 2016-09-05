<div id="category" data-collapse="#post" class="sn-content col-sm-9 col-md-10"><!-- Informacion - Contenido -->
    <div id="snwrap"><!-- #snwarp -->
        <div id="header" class="clearfix">
            <br/>
            <h1>Categorías <a href="<?php echo $data['siteUrl']; ?>admin/category/insert" class="btn btn-default">Nuevo</a></h1>
        </div>
        <div id="content"><!-- #content -->
            <div id="reloadData">
                <?php require \VIEWS_ADMIN . 'pagednav.php'; ?>
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
                            <?php
                            foreach ($data['categories'] as $category) {
                                $output = '<tr>';
                                $output .= '<td class="options">';
                                $output .= '<a class="btnAction-sm btn btn-primary" href="' . $data['siteUrl'] . 'admin/category/update/' . $category->getID() . '" title="Editar"><span class="glyphicon glyphicon-edit"></span></a> ';
                                $output .= '<a class="btnAction-sm btn btn-danger" href="' . $data['siteUrl'] . 'admin/category/delete/' . $category->getID() . $paged . '" title="Editar"><span class="glyphicon glyphicon-remove-sign"></span></a> ';
                                $output .= '</td>';
                                $output .= '<td>' . $category->getCategoryName() . '</td>';
                                $output .= '<td><span class="badge">' . $category->getCategoryCount() . '</span></td></tr>';
                                echo $output;
                            }
                            ?>
                        </tbody>
                    </table>
                </div><!-- #contentPost.table-responsive -->
                <?php require \VIEWS_ADMIN . 'pagednav.php'; ?>
            </div>
        </div><!-- #content -->
    </div><!-- #snwarp -->
</div><!-- Fin - Informacion - Contenido -->