<div id="category" data-collapse="#post" class="sn-content col-sm-9 col-md-10"><!-- Informacion - Contenido -->
    <div id="snwrap"><!-- #snwarp -->
        <div id="header" class="clearfix">
            <br/>
            <h1><?php echo $data['category']->isDefault() ? 'Publicar nueva' : 'Actualizar'; ?> categoría</h1>
        </div>
        <div id="content"><!-- #content -->
            <div class="row clearfix">
                <form role="form" method="post">
                    <div id="content-left" class="col-sm-9">
                        <div class="form-group">
                            <label class="control-label">Nombre</label>
                            <input class="form-control" type="text" name="categoryName" placeholder="Escribe el título" value="<?php echo $data['category']->getCategoryName(); ?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Descripción</label>
                            <textarea class="form-control" name="categoryDescription" rows="5"><?php echo $data['category']->getCategoryDescription(); ?></textarea>
                        </div>
                    </div>
                    <div id="content-right" class="col-sm-3">
                        <div class="panel panel-default">
                            <div class="panel-heading">Publicación</div>
                            <div class="panel-body">
                                <?php
                                if ($data['category']->isDefault()) {
                                    echo '<button class="btn btn-primary btn-block" type="submit" name="publish" value="publish">Publicar</button>';
                                } else {
                                    echo '<button class="btn btn-primary btn-block" type="submit" name="update" value="update">Actualizar</button>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php $data['template']::getTokenForm(); ?>
                </form>
            </div>
        </div><!-- #content -->
    </div><!-- #snwarp -->
</div><!-- Fin - Contenido -->
