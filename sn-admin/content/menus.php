<?php
get_header();
get_sidebar();
?>
<div id="menus" class="sn-content col-sm-9 col-md-10"><!-- Informacion - Contenido -->
    <div id="snwrap"><!-- #snwarp -->
        <div id="header" class="clearfix">
            <br/>
            <h1>Menus</h1>
        </div>
        <div id="content"><!-- #content -->
            <div class="row clearfix">
                <div class="col-sm-4">
                    <form role="form" method="post">
                        <div class="form-group">
                            <label>Titulo</label>
                            <input type="text" class="form-control input-lg" name="menu_title" placeholder="Escribe el título" value="<?php echo $menu['menu_title']; ?>">
                        </div>
<!--                        <div class="form-group">
                            <label>Slug</label>
                            <input type="text" class="form-control input-lg" name="menu_name" placeholder="menu_name" value="<?php // echo $menu['menu_name']; ?>">
                        </div>-->
                        <div class="form-group">
                            <label>Enlace asociado</label>
                            <input type="text" class="form-control input-lg" name="menu_url" placeholder="Enlace del menu" value="<?php echo $menu['menu_url']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Menú padre</label>
                            <select name="menu_sub" class="form-control">
                                
                                <?php
                                echo '<option value="0">--ninguno--</option>';
                                if(!empty($dataTable['menu']['select']) && $menu['menu_sub']){
                                    echo '<option value="'.$dataTable['menu']['select']->ID.'" selected>* '.$dataTable['menu']['select']->menu_title.'</option>';
                                }
                                foreach ($menuList as $menus) {
//                                    if($menus['ID'] != $menu['ID']){
                                        $selected = '';
                                        if($menu['menu_sub'] == $menus['ID']){
                                            $selected = ' selected';
                                        }
                                        echo "<option value='$menus[ID]'$selected>$menus[menu_title]</option>";
//                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <?php
                        if (filter_input(INPUT_GET, 'action') && $_GET['action'] == 'edit') {
                            echo '<button class="btn btn-primary btn-block" type="submit" name="update" value="update">Actualizar</button>';
                        } else {
                            echo '<button class="btn btn-primary btn-block" type="submit" name="publish" value="publish">Publicar</button>';
                        }
                        ?>
                    </form>
                </div>
                <div class="col-sm-8">
                    <?php reloadData(); ?>
                </div>
            </div>
        </div><!-- #content -->
        <?php get_credits(); ?>
    </div><!-- #snwarp -->
</div><!-- Fin - Informacion - Contenido -->
<?php
get_footer();
