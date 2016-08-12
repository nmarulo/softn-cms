<div id="index" class="sn-content col-sm-9 col-md-10"><!-- Informacion - Contenido -->
    <div id="snwrap"><!-- #snwarp -->
        <div id="header" class="clearfix">
            <h1>Información general</h1>
        </div>
        <div id="content"><!-- #content -->
            <div class="row clearfix">
                <div id="content-left" class="col-sm-6">
                    <div class="panel panel-primary"><!-- Estadisticas Generales -->
                        <div class="panel-heading">Estadisticas Generales</div>
                        <div class="panel-body">
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <span class="badge"><?php echo $data['count']['post']; ?></span>
                                    <a href="<?php echo $data['siteUrl']; ?>admin/post">Publicaciones</a>
                                </li>
                                <li class="list-group-item">
                                    <span class="badge"><?php echo $data['count']['page']; ?></span>
                                    <a href="<?php echo $data['siteUrl']; ?>admin/page">Paginas</a>
                                </li>
                                <li class="list-group-item">
                                    <span class="badge"><?php echo $data['count']['comment']; ?></span>
                                    <a href="<?php echo $data['siteUrl']; ?>admin/comment">Comentarios</a>
                                </li>
                                <li class="list-group-item">
                                    <span class="badge"><?php echo $data['count']['category']; ?></span>
                                    <a href="<?php echo $data['siteUrl']; ?>admin/category">Categorias</a>
                                </li>
                                <li class="list-group-item">
                                    <span class="badge"><?php echo $data['count']['user']; ?></span>
                                    <a href="<?php echo $data['siteUrl']; ?>admin/user">Usuarios</a>
                                </li>
                            </ul>
                        </div>
                    </div><!-- Fin - Estadisticas Generales -->
                    <div class="panel panel-primary"><!-- Categorias -->
                        <div class="panel-heading">Noticias de SoftN CMS</div>
                        <div class="panel-body">
                            <p>Ultima actualización: <?php echo $data['github']['lastUpdate']; ?> <a href="https://github.com/nmarulo/softn-cms" target="_blank">GitHub</a></p>
                            <ul class="list-group">
                                <?php foreach ($data['github']['entry'] as $value) { ?>
                                    <li class="list-group-item">
                                        <a href="<?php echo $value['authorUri']; ?>" target="_blank"><span class="label label-success"><?php echo $value['authorName']; ?></span></a> 
                                        <a href="<?php echo $value['linkHref']; ?>" target="_blank"><?php echo $value['title']; ?></a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div><!-- Fin - Categorias -->
                </div>
                <div id="content-right" class="col-sm-6">
                    <div class="panel panel-primary"><!-- Publicaciones - Entradas -->
                        <div class="panel-heading">Publicaciones</div>
                        <div class="panel-body">
                            <ul class="list-group">
                                <?php foreach ($data['lastPosts'] as $value) { ?>
                                    <li class="list-group-item clearfix">
                                        <span class="pull-left"><?php echo $value->getPostDate(); ?></span><a href='#'><?php echo $value->getPostTitle(); ?></a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div><!-- Fin - Publicaciones - Entradas -->
                    <div class="panel panel-primary"><!-- comentarios -->
                        <div class="panel-heading">Comentarios</div>
                        <div class="panel-body">
                            <ul class="list-group">
                                <li class="list-group-item clearfix">
                                    <span class="pull-left">fecha</span><a href='#'>titulo</a>
                                </li>
                            </ul>
                        </div>
                    </div><!-- Fin - comentarios -->
                </div>
            </div>
        </div><!-- #content -->
    </div><!-- #snwarp -->
</div><!-- Fin - Informacion - Contenido -->