<div id="post" data-collapse="#post" class="sn-content col-sm-9 col-md-10"><!-- Contenido -->
    <div id="snwrap"><!-- #snwarp -->
        <div id="header" class="clearfix">
            <br/>
            <h1><?php echo $data['post']->isDefault() ? 'Publicar nueva' : 'Actualizar' ?> entrada</h1>
        </div>
        <div id="content"><!-- #content -->
            <div class="row clearfix">
                <form role="form" method="post">
                    <div id="content-left" class="col-sm-9">
                        <div class="form-group">
                            <input type="text" class="form-control input-lg" name="postTitle" placeholder="Escribe el título" value="<?php echo $data['post']->getPostTitle(); ?>">
                        </div>
                        <?php
                        if (!$data['post']->isDefault()) {
                            echo '<div class="form-group">
                            <label>Enlace: <a href="' . $data['post']->getUrl('', FALSE) . '" target="_blank">' . $data['post']->getUrl('', FALSE) . '</a></label></div>';
                        }
                        ?>
                        <div class="form-group">
                            <label class="control-label">Contenido de la entrada</label>
                            <textarea id="textContent" class="form-control" name="postContents" rows="5"><?php echo $data['post']->getPostContents(); ?></textarea>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="checkbox">
                                    <label>
                                        <input name="commentStatus" type="checkbox" <?php echo $data['post']->getCommentStatus() ? 'checked' : ''; ?>> Habilitar comentarios
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="content-right" class="col-sm-3">
                        <div class="panel panel-default">
                            <div class="panel-heading">Publicación</div>
                            <div class="panel-body">
                                <div class="form-group checkbox">
                                    <label>
                                        <input name="postStatus" type="checkbox" <?php echo $data['post']->getPostStatus() ? 'checked' : ''; ?>> Visible
                                    </label>
                                </div>
                                <?php
                                if ($data['post']->isDefault()) {
                                    echo '<button class="btn btn-primary btn-block" type="submit" name="publish" value="publish">Publicar</button>';
                                } else {
                                    $str = '<p>Ultima actualización: <span class="label label-warning"><span class="glyphicon glyphicon-time"></span> ' . $data['post']->getPostUpdate() . '</span></p>';
                                    $str .= '<button class="btn btn-primary btn-block" type="submit" name="update" value="update">Actualizar</button>';
                                    echo $str;
                                }
                                ?>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">Categorías</div>
                            <div class="panel-body">
                                <select name="relationshipsCategoriesID[]" multiple class="form-control">
                                    <?php
                                    foreach ($data['categories'] as $category) {
                                        $categoryID = $category->getID();
                                        $selected   = '';
    
                                        if($data['isSelectOption']($categoryID, $data, 'relationshipsCategoriesID')){
                                            $selected = 'selected';
                                        }
    
                                        echo "<option value='$categoryID' $selected>" . $category->getCategoryName() . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">Etiquetas</div>
                            <div class="panel-body">
                                <select name="relationshipsTermsID[]" multiple class="form-control">
                                    <?php
                                    foreach ($data['terms'] as $term) {
                                        $termID   = $term->getID();
                                        $selected = '';
    
                                        if($data['isSelectOption']($termID, $data, 'relationshipsTermsID')){
                                            $selected = 'selected';
                                        }
                                        
                                        echo "<option value='$termID' $selected>" . $term->getTermName() . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <?php $data['template']::getTokenForm(); ?>
                </form>
            </div>
        </div><!-- #content -->
    </div><!-- #snwarp -->
</div><!-- Fin - Contenido -->
