<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php $data['template']->getPageTitle(); ?></title>
        <link rel="icon" href="<?php $data['template']->getUrlTheme(); ?>img/logo_32x32.png" sizes="32x32">
        <link href="<?php $data['template']->getUrlTheme(); ?>css/normalize.css" rel="stylesheet" type="text/css"/>
        <!-- Bootstrap -->
        <link href="<?php $data['template']->getUrlSite(); ?>app/vendor/twbs/bootstrap/dist/css/bootstrap.css" rel="stylesheet" type="text/css"/>
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
                                <a class="navbar-brand" href="<?php $data['template']->getUrlSite(); ?>">
                                    <?php $data['template']->getTitle(); ?>
                                </a>
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
                                    <?php if ($data['template']->isLogin()) { ?>
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php $data['template']->getSessionUserName(); ?> <span class="caret"></span></a>
                                            <ul class="dropdown-menu">
                                                <li><a href="<?php $data['template']->getUrlAdmin(); ?>">Administración</a></li>
                                                <li><a href="<?php $data['template']->getUrlProfile(); ?>">Perfil</a></li>
                                                <li role="separator" class="divider"></li>
                                                <li><a href="<?php $data['template']->getUrlLogout(); ?>">Cerrar sesión</a></li>
                                            </ul>
                                        </li>
                                    <?php } else { ?>
                                        <li><a href="<?php $data['template']->getUrlLogin(); ?>">Acceder</a></li>
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
                                            <a href="<?php $post->getPostUrl(); ?>"><?php $post->getPostTitle(); ?></a>
                                        </h2>
                                    </div>
                                    <p class="meta">
                                        <time class="label label-primary" datetime="2015/01/22"><span class="glyphicon glyphicon-time"></span> <?php $post->getPostDate(); ?></time>
                                        <span class="glyphicon glyphicon-user"></span> Publicado por 
                                        <a href="<?php $post->getPostUrlAuthor(); ?>">
                                            <?php $post->getPostAuthor(); ?>
                                        </a> 
                                        <?php if ($post->hasPostCategories()) { ?>
                                            /
                                            <span class=" glyphicon glyphicon-folder-open"></span> Archivado en 
                                            <?php foreach ($post->getPostCategories() as $category) { ?>
                                                <a class="label label-default" href="<?php $category->getCategoryUrl(); ?>">
                                                    <?php $category->getCategoryName(); ?>
                                                </a>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </p>
                                </header>
                                <section><?php echo $post->getPostContents(); ?></section>
                                <footer>
                                    <?php if ($post->hasPostTerms()) { ?>
                                        <p>
                                            Etiquetas: 
                                            <?php foreach ($post->getPostTerms() as $terms) { ?>
                                                <a class="label label-default" href="<?php $terms->getTermUrl(); ?>">
                                                    <?php $terms->getTermName(); ?>
                                                </a>
                                            <?php } ?>
                                        </p>
                                    <?php } ?>
                                </footer>
                            </article>
                            <!-- comentarios -->
                            <?php if ($post->hasPostComments()) { ?>
                                <div id="container-comments" class="clearfix">
                                    <h2>
                                        Comentarios 
                                        <small>
                                            <span class="label label-warning">
                                                <?php echo $post->getPostCommentCount(); ?>
                                            </span>
                                        </small>
                                    </h2>
                                    <?php foreach ($post->getPostComments() as $comment) { ?>
                                        <div id="<?php $comment->getCommentID(); ?>" class="media">
                                            <div class="media-left">
                                                <?php if ($comment->isCommentUrlAuthor()) { ?>
                                                    <a href="<?php $comment->getCommentUrlAuthor(); ?>">
                                                        <img class="media-object" src="<?php $comment->getCommentAvatar(); ?>">
                                                    </a>
                                                <?php } else { ?>
                                                    <img class="media-object" src="<?php $comment->getCommentAvatar(); ?>">
                                                <?php } ?>
                                            </div>
                                            <div class="media-body">
                                                <div class="row clearfix">
                                                    <div class="col-md-6">
                                                        <strong><?php $comment->getCommentAuthor(); ?></strong>
                                                    </div>
                                                    <div class="col-md-6"><?php $comment->getCommentDate(); ?></div>
                                                </div>
                                                <p><?php $comment->getCommentContents(); ?></p>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <?php
                            }

                            if ($post->getPostCommentStatus()) {
                                ?>
                                <div id="container-comments-form" class="clearfix">
                                    <h2>Publicar comentario</h2>
                                    <form method="post">
                                        <?php if ($data['template']->isLogin()) { ?>
                                            <p>
                                                Conectado como <strong>
                                                    <?php $data['template']->getSessionUserName(); ?></strong>
                                            </p>
                                            <input type="hidden" name="commentUserID" value="<?php $data['template']->getSessionUserID(); ?>"/>
                                        <?php } else { ?>
                                            <div class="form-group">
                                                <label class="control-label">Nombre</label>
                                                <input type="text" class="form-control" name="commentAutor"/>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Correo electronico</label>
                                                <input type="email" class="form-control" name="commentAuthorEmail"/>
                                            </div>
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
        <script src="<?php $data['template']->getUrlSite(); ?>app/views/js/jquery-1.12.0.js" type="text/javascript"></script>
        <script src="<?php $data['template']->getUrlSite(); ?>app/vendor/twbs/bootstrap/dist/js/bootstrap.js" type="text/javascript"></script>
    </body>
</html>