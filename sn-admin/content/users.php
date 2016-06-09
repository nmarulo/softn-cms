<?php
get_header();
get_sidebar();
?>
<div id="users" data-collapse="#users" class="sn-content col-sm-9 col-md-10"><!-- Informacion - Contenido -->
    <div id="snwrap"><!-- #snwarp -->
        <div id="header" class="clearfix">
            <br/>
            <h1>Usuarios <a href="user-new.php" class="btn btn-default">Nuevo usuario</a></h1>
        </div>
        <div id="reloadData"><!-- #content -->
            <?php reloadData(); ?>
        </div><!-- #content -->
        <?php get_credits(); ?>
    </div><!-- #snwarp -->
</div><!-- Fin - Informacion - Contenido -->
<?php
get_footer();
