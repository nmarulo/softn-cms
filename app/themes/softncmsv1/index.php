<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php $data['template']->getTitle(); ?></title>
        <link rel="icon" href="<?php $data['template']->getUrlTheme(); ?>img/logo_32x32.png" sizes="32x32">
        <link href="<?php $data['template']->getUrlTheme(); ?>css/normalize.css" rel="stylesheet" type="text/css"/>
        <!-- Bootstrap -->
        <link href="<?php echo $data['siteUrl']; ?>app/vendor/twbs/bootstrap/dist/css/bootstrap.css" rel="stylesheet" type="text/css"/>
        <link href="<?php $data['template']->getUrlTheme(); ?>css/style.css" rel="stylesheet" type="text/css"/>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="container"><!-- .container -->
            <div id="container"><!-- #container -->
                <header>
                    <nav class="navbar navbar-white">
                        <div class="container-fluid">
                            <!-- Brand and toggle get grouped for better mobile display -->
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                                <a class="navbar-brand" href="#"><?php echo $data['siteTitle']; ?></a>
                            </div>

                            <!-- Collect the nav links, forms, and other content for toggling -->
                            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                <ul class="nav navbar-nav">
                                    <li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
                                    <li><a href="#">Link</a></li>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="#">Action</a></li>
                                        </ul>
                                    </li>
                                </ul>
                                <form class="navbar-form navbar-left">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Buscar...">
                                    </div>
                                    <button type="submit" class="btn btn-default">Buscar</button>
                                </form>
                                <ul class="nav navbar-nav navbar-right">
                                    <?php if (empty($data['userSession'])) { ?>
                                        <li><a href="<?php echo $data['siteUrl'] . 'login'; ?>">Acceder</a></li>
                                    <?php } else { ?>
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $data['userSession']->getUserName(); ?> <span class="caret"></span></a>
                                            <ul class="dropdown-menu">
                                                <li><a href="<?php $data['template']->getUrlAdmin(); ?>">Administración</a></li>
                                                <li><a href="<?php $data['template']->getUrlProfile(); ?>">Perfil</a></li>
                                                <li role="separator" class="divider"></li>
                                                <li><a href="<?php $data['template']->getUrlLogout(); ?>">Cerrar sesión</a></li>
                                            </ul>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div><!-- /.navbar-collapse -->
                        </div><!-- /.container-fluid -->
                    </nav>
                </header>
                <div class="row clearfix">
                    <main class="col-sm-8">
                        <?php foreach ($data['posts'] as $post) { ?>
                            <article class="bg-grey">
                                <header class="clearfix">
                                    <div class="post-title clearfix">
                                        <h2 class="h3">
                                            <a href="<?php echo $data['siteUrl'] . 'post/' . $post->getID(); ?>"><?php echo $post->getPostTitle(); ?></a>
                                        </h2>
                                    </div>
                                    <p class="meta">
                                        <time class="label label-primary" datetime="2015/01/22"><span class="glyphicon glyphicon-time"></span> <?php echo $post->getPostDate(); ?></time>
                                        <span class="glyphicon glyphicon-user"></span> Publicado por <?php echo $post->getUser()->getUserName(); ?>/
                                        <span class=" glyphicon glyphicon-folder-open"></span> Archivado en categoria.
                                    </p>
                                </header>
                                <section><?php echo $post->getPostContents(); ?></section>
                                <footer>
                                    <p>Etiquetas: <a class="label label-default" href="#">titulo</a></p>
                                </footer>
                            </article>
                            <!-- comentarios -->
                            <?php if ($post->getCommentCount()) { ?>
                                <div id="container-comments" class="clearfix">
                                    <h2>
                                        Comentarios 
                                        <small>
                                            <span class="label label-warning">
                                                <?php echo $post->getCommentCount(); ?>
                                            </span>
                                        </small>
                                    </h2>
                                    <div id="comment-1" class="media">
                                        <div class="media-left">
                                            <a href="#">
                                                <img class="media-object" src="<?php echo $data['siteUrl']; ?>app/themes/default/img/avatar.svg">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <div class="row clearfix">
                                                <div class="col-md-6">
                                                    <a href="#" target="_blank">autor</a>
                                                </div>
                                                <div class="col-md-6">fecha</div>
                                            </div>
                                            <p>contenido</p>
                                        </div>
                                    </div>
                                    <div id="comment-2" class="media">
                                        <div class="media-left">
                                            <a href="#">
                                                <img class="media-object" src="<?php echo $data['siteUrl']; ?>app/themes/default/img/avatar.svg">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <div class="row clearfix">
                                                <div class="col-md-6">
                                                    <a href="#" target="_blank">autor</a>
                                                </div>
                                                <div class="col-md-6">fecha</div>
                                            </div>
                                            <p>contenido</p>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }

                            if ($post->getCommentStatus()) {
                                ?>
                                <div id="container-comments-form" class="clearfix">
                                    <h2>Publicar comentario</h2>
                                    <form method="post">
                                        <?php if (empty($data['userSession'])) { ?>
                                            <div class="form-group">
                                                <label class="control-label">Nombre</label>
                                                <input type="text" class="form-control" name="commentAutor"/>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Correo electronico</label>
                                                <input type="email" class="form-control" name="commentAuthorEmail"/>
                                            </div>
                                        <?php } else { ?>
                                            <p>
                                                Conectado como <strong>
                                                <?php echo $data['userSession']->getUserName(); ?></strong>
                                            </p>
                                            <input type="hidden" name="commentAutorID" value="<?php echo $data['userSession']->getID(); ?>"/>
                                        <?php } ?>
                                        <div class="form-group">
                                            <label class="control-label">Comentario</label>
                                            <textarea class="form-control" name="commentContents" rows="5"></textarea>
                                        </div>
                                        <button class="btn btn-primary" name="publish" value="publish" type="submit">Publicar</button>
                                    </form>
                                </div>
                            <?php } else { ?>
                                <div class="alert alert-info">Los comentarios estan cerrados.</div>
                                <?php
                            }//end if
                        }

                        // PAGINACION ==========================================
                        $pagination = $data['pagination'];

                        if ($pagination->isShowPagination()) {
                            $output = "";
                            $href = $pagination->getUrl() . 'paged';
                            $dataPaged = $pagination->getDataPaged();
                            $leftArrow = $pagination->getDataLeftArrow();
                            $rightArrow = $pagination->getDataRightArrow();
                            $outLeftArrow = "<li><a data-paged='' href='$href/$leftArrow'><span>&laquo;</span></a></li>";
                            $outRightArrow = "<li><a data-paged='' href='$href/$rightArrow'><span>&raquo;</span></a></li>";

                            if (empty($leftArrow)) {
                                $outLeftArrow = '<li class="disabled"><a href="#"><span>&laquo;</span></a></li>';
                            }

                            if (empty($rightArrow)) {
                                $outRightArrow = '<li class="disabled"><a href="#"><span>&raquo;</span></a></li>';
                            }

                            foreach ($dataPaged as $value) {
                                //Si es la pagina actual se agrega la clase "active".
                                $active = $value['active'] ? 'class="active"' : '';
                                $output .= "<li $active><a data-paged='$value[dataPaged]' href='$href/$value[dataPaged]'>$value[page]</a></li>";
                            }
                            ?>
                            <div class="pagination-content">
                                <ul class="pagination clearfix">
                                    <?php echo $outLeftArrow . $output . $outRightArrow; ?>
                                </ul>
                            </div>
                            <?php
                        }
                        ?>
                    </main>
                    <aside class="col-sm-4">
                        <div class="sidebar">
                            <div class="sidebar-title">Titulo</div>
                            <div class="sidebar-content">contenido</div>
                        </div>
                    </aside>
                </div>
                <footer class="clearfix">
                    <p class="pull-right">By <a href="http://softn.red/" title="Portal SoftN" target="_blank">SoftN</a></p>
                    <p class="pull-left">&copy; SoftN CMS</p>
                </footer>
            </div><!-- #container-->
        </div><!-- .container -->
        <script src="<?php echo $data['siteUrl']; ?>app/views/js/jquery-1.12.0.js" type="text/javascript"></script>
        <script src="<?php echo $data['siteUrl']; ?>app/vendor/twbs/bootstrap/dist/js/bootstrap.js" type="text/javascript"></script>
    </body>
</html>