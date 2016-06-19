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
                    <div id="formGroup">
                        <div class="form-group">
                            <label>Titulo</label>
                            <input type="text" class="form-control input-lg" name="menu_title" placeholder="Escribe el título" value="<?php echo $menu['menu_title']; ?>">
                        </div>
                        <?php if ($action_edit) { ?>
                            <div class="form-group">
                                <label>Slug</label>
                                <input type="text" class="form-control input-lg" name="menu_name" placeholder="menu_name" value="<?php echo $menu['menu_name']; ?>">
                            </div>
                        <?php } ?>
                        <div class="form-group">
                            <label>Enlace asociado</label>
                            <input type="text" class="form-control input-lg" name="menu_url" placeholder="Enlace del menu" value="<?php echo $menu['menu_url']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Menú padre</label>
                            <select name="menu_sub" class="form-control">
                                <option value="0">--ninguno--</option>
                                <?php
                                $str = '';
                                //Si no se ha seleccionado un menu, se muestran los menus padres.
                                if ($selectMenu) {
                                    //Si edito un menu padre, se muestran todos los menus padres.
                                    if ($selectMenu->getID() == $menu['ID']) {
                                        foreach ($menuParents as $parent) {
                                            //Para evitar que se puede elegir asi mismo como menu padre.
                                            if ($selectMenu->getID() != $parent['ID']) {
                                                $str .= "<option value='$parent[ID]'>$parent[menu_title]</option>";
                                            }
                                        }
                                    } else {
                                        $str .= '<option value="' . $selectMenu->getID() . '" selected>* ' . $selectMenu->getMenu_title() . '</option>';
                                        foreach ($menuList as $menus) {
                                            //Para evitar que se puede elegir asi mismo como menu padre.
                                            if ($menu['ID'] != $menus['ID']) {
                                                $str .= "<option value='$menus[ID]'>$menus[menu_title]</option>";
                                            }
                                        }
                                    }
                                } else {
                                    foreach ($menuParents as $parent) {
                                        $str .= "<option value='$parent[ID]'>$parent[menu_title]</option>";
                                    }
                                }
                                echo $str;
                                ?>
                            </select>
                        </div>
                        <?php
                        $id = $selectMenu ? $selectMenu->getID() : 0;
                        if ($action_edit) {
                            echo "<button class='btnAction btn btn-primary btn-block' data-action='update=$menu[ID]&selectMenu=$id'>Actualizar</button>";
                        } else {
                            echo '<button class="btnAction btn btn-primary btn-block" data-action="publish=1&selectMenu=' . $id . '">Publicar</button>';
                        }
                        ?>
                    </div>
                </div>
                <div id="reloadData" class="col-sm-8">
                    <?php reloadData(); ?>
                </div>
            </div>
        </div><!-- #content -->
        <?php get_credits(); ?>
    </div><!-- #snwarp -->
</div><!-- Fin - Informacion - Contenido -->
<?php
get_footer();
