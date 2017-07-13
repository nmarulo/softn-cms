<?php
use SoftnCMS\controllers\ViewController;

$optionTitle       = ViewController::getViewData('optionTitle');
$optionDescription = ViewController::getViewData('optionDescription');
$optionPaged       = ViewController::getViewData('optionPaged');
$optionSiteUrl     = ViewController::getViewData('optionSiteUrl');
$optionTheme       = ViewController::getViewData('optionTheme');
$optionMenu        = ViewController::getViewData('optionMenu');
$optionEmailAdmin  = ViewController::getViewData('optionEmailAdmin');
$listThemes        = ViewController::getViewData('listThemes');
$currentThemeName  = $optionTheme->getOptionValue();
?>

<div class="page-container">
    <div>
        <h1>Configuración general</h1>
    </div>
    <div>
        <form class="form-horizontal" role="form" method="post">
            <div class="form-group">
                <label class="col-sm-2 control-label">Título del sitio</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="<?php echo $optionTitle->getOptionName(); ?>" value="<?php echo $optionTitle->getOptionValue(); ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Descripción corta</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="<?php echo $optionDescription->getOptionName(); ?>" value="<?php echo $optionDescription->getOptionValue(); ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">E-mail administrador</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" name="<?php echo $optionEmailAdmin->getOptionName(); ?>" value="<?php echo $optionEmailAdmin->getOptionValue(); ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Dirección URL</label>
                <div class="col-sm-10">
                    <input type="url" class="form-control" name="<?php echo $optionSiteUrl->getOptionName(); ?>" value="<?php echo $optionSiteUrl->getOptionValue(); ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Paginación</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" name="<?php echo $optionPaged->getOptionName(); ?>" min="1" value="<?php echo $optionPaged->getOptionValue(); ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Seleccionar plantilla</label>
                <div class="col-sm-10">
                    <select class="form-control" name="<?php echo $optionTheme->getOptionName(); ?>">
                        <?php
                        array_walk($listThemes, function($themeName) use ($currentThemeName) {
                            $selected = '';
                            
                            if($themeName == $currentThemeName){
                                $selected = 'selected';
                            }
    
                            echo "<option value='$themeName' $selected>$themeName</option>";
                        });
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button class="btn btn-primary" type="submit" name="update" value="update">Guardar cambios</button>
                </div>
            </div>
        </form>
    </div>
</div>
