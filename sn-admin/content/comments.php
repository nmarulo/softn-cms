<?php
get_header();
get_sidebar();
?>
<div id="comments" class="sn-content col-sm-9 col-md-10"><!-- Informacion - Contenido -->
    <div id="snwrap"><!-- #snwarp -->
        <div id="header" class="clearfix">
            <br/>
            <h1>Comentarios</h1>
        </div>
        <div id="reloadData"><!-- #content -->
            <?php reloadData(); ?>
        </div><!-- #content -->
        <?php get_credits(); ?>
    </div><!-- #snwarp -->
</div><!-- Fin - Informacion - Contenido -->
<?php
get_footer();
