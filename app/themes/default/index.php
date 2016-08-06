<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $data['siteTitle']; ?></title>
        <link rel="icon" href="logo_32x32.png" sizes="32x32">
        <!-- Bootstrap -->
        <link href="<?php echo $data['siteUrl']; ?>app/vendor/twbs/bootstrap/dist/css/bootstrap.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo $data['siteUrl']; ?>app/themes/default/css/style.css" rel="stylesheet" type="text/css"/>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div id="headerBg">
            <header class="container"><!-- header.container -->
                <br/>
                <nav class="navbar navbar-default"><!-- nav.navbar -->
                    <div class="container-fluid">
                        <!-- Brand and toggle get grouped for better mobile display -->
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="<?php echo $data['siteUrl']; ?>"><?php echo $data['siteTitle']; ?></a>
                        </div>

                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                            <ul class="nav navbar-nav">
                                <li><a href='#'>titulo</a></li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                        titulo <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href='#'>titulo</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div><!-- /.navbar-collapse -->
                    </div><!-- /.container-fluid -->
                </nav><!-- nav.navbar -->
                <div id="logo" class="center-block">
                </div>
            </header><!-- header.container -->
        </div>
        <br/>
        <div class="container"><!-- .container -->
            <div id="content" class="row clearfix"><!-- .row.clearfix -->
                <div id="main" class="col-md-8"><!-- #main -->
                    <article id="post-" class="">
                        <header>
                            <div class="page-header">
                                <h1 class="h2">
                                    <a href="#">titulo</a>
                                </h1>
                            </div>
                            <p class="meta">
                                <time class="label label-primary" datetime="2015/01/22"><span class="glyphicon glyphicon-time"></span> 2015/01/22</time>
                                <span class="glyphicon glyphicon-user"></span> Publicado por autor/
                                <span class=" glyphicon glyphicon-folder-open"></span> Archivado en categoria.
                            </p>
                            <hr />
                        </header>
                        <section class="post_content clearfix">
                            contenido
                        </section>
                        <hr/>
                        <footer>
                            <p>Etiquetas: <a class="label label-default" href="#">titulo</a></p>
                        </footer>
                    </article>
                    <div>
                        <h2>Comentarios</h2>
                        <div id="comment-#" class="media">
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
                </div><!-- #main -->

                <div id="sidebar" class="col-md-4"><!-- #sidebar -->
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <a href="<?php echo $data['siteUrl']; ?>admin">Administración</a>
                        </div>
                    </div>
                    <div id="" class="widget panel panel-default">
                        <div class="titleWidget panel-heading">
                            <h4 class="">titulo</h4>
                        </div>
                        <div class="contentWidget panel-body">
                            contenido
                        </div>
                    </div>
                </div><!-- #sidebar -->
            </div><!-- .row.clearfix -->
        </div><!-- .container -->
        <footer class="container"><!-- footer.container -->
            <br/>
            <p class="pull-right">By <a href="http://softn.red/" title="Portal SoftN" target="_blank">SoftN</a></p>
            <p class="pull-left">&copy; SoftN CMS</p>
        </footer><!-- footer.container -->
        <script src="<?php echo $data['siteUrl']; ?>app/views/js/jquery-1.12.0.js" type="text/javascript"></script>
        <script src="<?php echo $data['siteUrl']; ?>app/vendor/twbs/bootstrap/dist/js/bootstrap.js" type="text/javascript"></script>
    </body>
</html>