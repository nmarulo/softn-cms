<?php
get_header();
get_sidebar();
?>
<div id="posts" data-collapse="#posts" class="sn-content col-sm-9 col-md-10"><!-- Informacion - Contenido -->
    <div id="snwrap"><!-- #snwarp -->
        <div id="header" class="clearfix">
            <br/>
            <h1>Publicaciones <a href="post-new.php" class="btn btn-default">Nueva entrada</a></h1>
        </div>
        <div id="reloadData"><?php reloadData(); ?></div><!-- #content -->
        <?php get_credits(); ?>
    </div><!-- #snwarp -->
</div><!-- Fin - Informacion - Contenido -->
<?php
get_footer();
