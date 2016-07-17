<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SoftN CMS</title>
        <link href="../vendor/twbs/bootstrap/dist/css/bootstrap.css" rel="stylesheet" type="text/css"/>
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <header>
            <nav class="navbar navbar-custom"><!-- nav.navbar -->
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbarHeader">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#">titulo</a>
                    </div>
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="navbarHeader">
                        <ul class="nav navbar-nav">
                            <li class="dropdown">
                                <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    Publicar
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="post-new.php">Entrada</a></li>
                                    <li><a href="page-new.php">Pagina</a></li>
                                    <li><a href="categories.php">Categoría</a></li>
                                    <li><a href="terms.php">Etiqueta</a></li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <span class="glyphicon glyphicon-user"></span>
                                    Hola 
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#">Perfil</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="?action=logout" role="link">Cerrar sesión</a></li>
                                </ul>
                            </li>
                        </ul>
                        <div id="search_admin" class="navbar-form navbar-right">
                            <input type="text" class="form-control" placeholder="Buscar..." name="search_admin" >
                        </div>
                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </nav><!-- nav.navbar -->
        </header>
        <div class="container-fluid"><!-- .container-fluid -->
            <div class="row clearfix"><!-- .row.clearfix -->
                <aside class="col-sm-3 col-md-2 sn-menu-content">
                    <ul class="sn-menu">
                        <li>
                            <a data-toggle='collapse' href='#'>titulo <span class='pull-right glyphicon glyphicon-chevron-down'></span></a>
                            <ul id='#' class='sn-submenu collapse'>
                                <li><a href='#'>titulo</a></li>
                                <li><a href='#'>titulo</a></li>
                            </ul>
                        </li>
                        <li><a href='#'>titulo</a></li>
                        <li><a href='#'>titulo</a></li>
                    </ul>
                </aside>
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
                                                    <span class="badge">0</span>
                                                    <a href="posts.php">Publicaciones</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <span class="badge">0</span>
                                                    <a href="pages.php">Paginas</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <span class="badge">0</span>
                                                    <a href="comments.php">Comentarios</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <span class="badge">0</span>
                                                    <a href="categories.php">Categorias</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <span class="badge">0</span>
                                                    <a href="users.php">Usuarios</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div><!-- Fin - Estadisticas Generales -->
                                    <div class="panel panel-primary"><!-- Categorias -->
                                        <div class="panel-heading">Noticias de SoftN CMS</div>
                                        <div class="panel-body">
                                            <p>Ultima actualización: <a href="https://github.com/nmarulo/softn-cms" target="_blank">GitHub</a></p>
                                        </div>
                                    </div><!-- Fin - Categorias -->
                                </div>
                                <div id="content-right" class="col-sm-6">
                                    <div class="panel panel-primary"><!-- Publicaciones - Entradas -->
                                        <div class="panel-heading">Publicaciones</div>
                                        <div class="panel-body">
                                            <ul class="list-group">
                                                <li class="list-group-item clearfix">
                                                    <span class="pull-left">fecha</span><a href='#'>titulo</a>
                                                </li>
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
                        <div id="footer" class="clearfix">
                            <hr class=""/>
                            <p class="pull-left">SoftN CMS</p>
                            <p class="pull-right">versión 0.3</p>
                        </div>
                    </div><!-- #snwarp -->
                </div><!-- Fin - Informacion - Contenido -->
            </div><!-- .row.clearfix -->
        </div><!-- .container-fluid -->
        <script src="js/jquery-1.12.0.js" type="text/javascript"></script>
        <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.js" type="text/javascript"></script>
        <script src="../vendor/tinymce/tinymce/tinymce.js" type="text/javascript"></script>
        <script src="js/script.js" type="text/javascript"></script>
    </body>
</html>
