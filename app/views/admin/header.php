<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php $data['template']::getTitle(); ?></title>
        <link href="<?php $data['template']::getUrl(); ?>app/views/css/normalize.css" rel="stylesheet" type="text/css"/>
        <link href="<?php $data['template']::getUrl(); ?>app/vendor/twbs/bootstrap/dist/css/bootstrap.css" rel="stylesheet" type="text/css"/>
        <link href="<?php $data['template']::getUrl(); ?>app/vendor/fortawesome/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?php $data['template']::getUrl(); ?>app/views/css/style.css" rel="stylesheet" type="text/css"/>
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
                        <a class="navbar-brand" href="<?php $data['template']::getUrl(); ?>"><?php $data['template']::getSiteTitle(); ?></a>
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
                                    <li><a href="<?php $data['template']::getUrlPostInsert(); ?>">Entrada</a></li>
                                    <li><a href="<?php $data['template']::getUrlAdmin(); ?>">Pagina</a></li>
                                    <li><a href="<?php $data['template']::getUrlCategoryInsert(); ?>">Categoría</a></li>
                                    <li><a href="<?php $data['template']::getUrlTermInsert(); ?>">Etiqueta</a></li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <span class="glyphicon glyphicon-user"></span>
                                    Hola <?php $data['template']::getUserName(); ?>
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="<?php $data['template']::getUrlUserUpdateSession(); ?>">Perfil</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="<?php $data['template']::getUrlLogout(); ?>" role="link">Cerrar sesión</a></li>
                                </ul>
                            </li>
                        </ul>
                        <div id="search_admin" class="navbar-form navbar-right">
                            <input type="text" class="form-control" placeholder="Buscar..." name="searchAdmin" >
                        </div>
                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </nav><!-- nav.navbar -->
        </header>
        <div class="container-fluid"><!-- .container-fluid -->
            <div class="row clearfix"><!-- .row.clearfix -->
