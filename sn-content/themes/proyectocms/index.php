<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php siteTitle(true); ?></title>
        <link rel="icon" href="sn-admin/img/logo_32x32.png" sizes="32x32">
        <link href="sn-content/themes/<?php echo $dataTable['option']['theme'] . '/'; ?>normalize.css" rel="stylesheet" type="text/css"/>
        <link href="sn-content/themes/<?php echo $dataTable['option']['theme'] . '/'; ?>style.css" rel="stylesheet" type="text/css"/>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <header>
            <?php navMenu(); ?>
        </header>
        <div class="container">
            <div class="clearfix">
                <div class="col-left">
                    <?php while (have_posts()) { ?>
                        <section>
                            <header>
                                <h2><a href="<?php echo get_post_url(); ?>"><?php get_title(); ?></a></h2>
                                <p>Publicado por <?php get_author(); ?> / Archivado en <?php get_category(); ?></p>
                            </header>
                            <article>
                                <?php get_content(); ?>
                            </article>
                            <footer>
                                <p>Etiquetas: <?php get_terms(); ?></p>
                            </footer>
                        </section>
                        <?php
                        get_comments();
                    }
                    ?>
                </div>
                <div class="col-right">
                    <aside>
                        <div class=widgets">
                            <div class="widget-title">
                                <a href="sn-admin/">Administración</a>
                            </div>
                        </div>
                        <?php while (have_sibebars()) { ?>
                            <div class="widgets">
                                <h2 class="widget-title"><?php getSidebar_title(); ?></h2>
                                <div class="widget-content">
                                    <?php getSidebar_contents(); ?>
                                </div>
                            </div>
                        <?php } ?>
                    </aside>
                </div>
            </div>
        </div>
        <footer>
            <div class="container">
                <p>&copy; Proyecto SoftN-CMS</p>
                <p>By <a href="#">SoftN</a></p>
            </div>
        </footer>
    </body>
</html>
