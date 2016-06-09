<?php
get_header();
get_sidebar();
?>
<div id="pages" data-collapse="#pages" class="sn-content col-sm-9 col-md-10"><!-- Informacion - Contenido -->
    <div id="snwrap"><!-- #snwarp -->
        <div id="header" class="clearfix">
            <br/>
            <h1>Paginas <a href="page-new.php" class="btn btn-default">Nueva pagina</a></h1>
        </div>
        <div id="reloadData"><?php reloadData(); ?></div><!-- #content -->
        <?php get_credits(); ?>
    </div><!-- #snwarp -->
</div><!-- Fin - Informacion - Contenido -->
<?php
get_footer();
