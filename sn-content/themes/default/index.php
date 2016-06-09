<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php siteTitle(true); ?></title>
        <link rel="icon" href="sn-admin/img/logo_32x32.png" sizes="32x32">
        <!-- Bootstrap -->
        <link href="sn-includes/css/bootstrap.min.css" rel="stylesheet">
        <link href="sn-content/themes/<?php echo $dataTable['option']['theme'] . '/'; ?>css/style.css" rel="stylesheet" type="text/css"/>

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
                <?php getNavigationMenu(); ?>
                <div id="logo" class="center-block">
                </div>
            </header><!-- header.container -->
        </div>
        <br/>
        <div class="container"><!-- .container -->
            <div id="content" class="row clearfix"><!-- .row.clearfix -->
                <div id="main" class="col-md-8"><!-- #main -->
                    <?php
                    while (have_posts()) {
                        ?>
                        <article id="post-<?php get_post_ID(); ?>" class="">
                            <header>
                                <div class="page-header">
                                    <h1 class="h2">
                                        <a href="<?php echo get_post_url(); ?>"><?php get_title(); ?></a>
                                    </h1>
                                </div>
                                <p class="meta">
                                    <time class="label label-primary" datetime="2015/01/22"><span class="glyphicon glyphicon-time"></span> <?php get_date(); ?></time>
                                    <span class="glyphicon glyphicon-user"></span> Publicado por <?php get_author(); ?> /
                                    <span class=" glyphicon glyphicon-folder-open"></span> Archivado en <?php get_category(); ?>.
                                </p>
                                <hr />
                            </header>
                            <section class="post_content clearfix">
                                <?php get_content(); ?>
                            </section>
                            <hr/>
                            <footer>
                                <p>Etiquetas: <?php get_terms(); ?></p>
                            </footer>
                        </article>
                        <?php
                        get_comments();
                    }
                    ?>
                </div><!-- #main -->

                <div id="sidebar" class="col-md-4"><!-- #sidebar -->
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <a href="sn-admin/">Administración</a>
                        </div>
                    </div>
                    <?php
                    while (have_sibebars()) {
                        ?>
                        <div id="" class="widget panel panel-default">
                            <div class="titleWidget panel-heading">
                                <h4 class=""><?php getSidebar_title(); ?></h4>
                            </div>
                            <div class="contentWidget panel-body">
                                <?php getSidebar_contents(); ?>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div><!-- #sidebar -->
            </div><!-- .row.clearfix -->
        </div><!-- .container -->
        <footer class="container"><!-- footer.container -->
            <br/>
            <p class="pull-right">By <a href="http://softn.red/" title="Portal SoftN" target="_blank">SoftN</a></p>
            <p class="pull-left">&copy; SoftN CMS</p>
        </footer><!-- footer.container -->
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="sn-includes/js/bootstrap.min.js"></script>
        <script src="sn-content/themes/<?php echo $dataTable['option']['theme'] . '/'; ?>js/script.js" type="text/javascript"></script>
    </body>
</html>