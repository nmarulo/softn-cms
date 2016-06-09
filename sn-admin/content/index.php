<?php
get_header();
get_sidebar();
?>
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
                                    <span class="badge"><?php echo $dataTable['post']['count']; ?></span>
                                    <a href="posts.php">Publicaciones</a>
                                </li>
                                <li class="list-group-item">
                                    <span class="badge"><?php echo $dataTable['page']['count']; ?></span>
                                    <a href="pages.php">Paginas</a>
                                </li>
                                <li class="list-group-item">
                                    <span class="badge"><?php echo $dataTable['comment']['count']; ?></span>
                                    <a href="comments.php">Comentarios</a>
                                </li>
                                <li class="list-group-item">
                                    <span class="badge"><?php echo $dataTable['category']['count']; ?></span>
                                    <a href="categories.php">Categorias</a>
                                </li>
                                <li class="list-group-item">
                                    <span class="badge"><?php echo $dataTable['user']['count']; ?></span>
                                    <a href="users.php">Usuarios</a>
                                </li>
                            </ul>
                        </div>
                    </div><!-- Fin - Estadisticas Generales -->
                    <div class="panel panel-primary"><!-- Categorias -->
                        <div class="panel-heading">Noticias de SoftN CMS</div>
                        <div class="panel-body">
                            <p>Ultima actualización: <?php echo $lastUpdateGit; ?> <a href="https://github.com/nmarulo/softn-cms" target="_blank">GitHub</a></p>
                            <?php echo $dataTable['index']['github']; ?>
                        </div>
                    </div><!-- Fin - Categorias -->
                </div>
                <div id="content-right" class="col-sm-6">
                    <div class="panel panel-primary"><!-- Publicaciones - Entradas -->
                        <div class="panel-heading">Publicaciones</div>
                        <div class="panel-body">
                                <?php
                                echo $dataTable['index']['post'];
                                ?>
                        </div>
                    </div><!-- Fin - Publicaciones - Entradas -->
                    <div class="panel panel-primary"><!-- comentarios -->
                        <div class="panel-heading">Comentarios</div>
                        <div class="panel-body">
                                <?php
                                echo $dataTable['index']['comment'];
                                ?>
                        </div>
                    </div><!-- Fin - comentarios -->
                </div>
            </div>
        </div><!-- #content -->
        <?php get_credits(); ?>
    </div><!-- #snwarp -->
</div><!-- Fin - Informacion - Contenido -->
<?php
get_footer();
