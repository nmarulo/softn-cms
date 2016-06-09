<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo siteTitle() . ' | ' . $title; ?></title>

        <?php getStyles(); ?>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <?php Messages::show(); ?>
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
                        <a class="navbar-brand" href="<?php siteUrl(true); ?>"><?php siteTitle(true); ?></a>
                    </div>
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="navbarHeader">
                        <?php if (SN_Users::checkRol('editor')) { ?>
                            <ul class="nav navbar-nav">
                                <li class="dropdown">
                                    <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                        Publicar
                                        <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a href="post-new.php">Entrada</a></li>
                                        <?php if (SN_Users::checkRol('author')) { ?>
                                            <li><a href="page-new.php">Pagina</a></li>
                                            <li><a href="categories.php">Categoría</a></li>
                                            <li><a href="terms.php">Etiqueta</a></li>
                                        <?php } ?>
                                    </ul>
                                </li>
                            </ul>
                        <?php } ?>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <span class="glyphicon glyphicon-user"></span>
                                    Hola <?php echo SN_Users::getSession()->getUser_name(); ?>
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="user-new.php?action=edit&id=<?php echo SN_Users::getSession()->getID(); ?>">Perfil</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="?action=logout" role="link">Cerrar sesión</a></li>
                                </ul>
                            </li>
                        </ul>
                        <?php if (SN_Users::checkRol('editor')) { ?>
                            <div id="search_admin" class="navbar-form navbar-right">
                                <input type="text" class="form-control" placeholder="Buscar..." name="search_admin" >
                            </div>
                        <?php } ?>
                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </nav><!-- nav.navbar -->
        </header>
        <div class="container-fluid"><!-- .container-fluid -->
            <div class="row clearfix"><!-- .row.clearfix -->
