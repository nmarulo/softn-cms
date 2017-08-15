<?php

use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\tables\Menu;

$optionTitle       = ViewController::getViewData('optionTitle');
$optionDescription = ViewController::getViewData('optionDescription');
$optionPaged       = ViewController::getViewData('optionPaged');
$optionSiteUrl     = ViewController::getViewData('optionSiteUrl');
$optionTheme       = ViewController::getViewData('optionTheme');
$menuList          = ViewController::getViewData('menuList');
$optionMenu        = ViewController::getViewData('optionMenu');
$optionEmailAdmin  = ViewController::getViewData('optionEmailAdmin');
$listThemes        = ViewController::getViewData('listThemes');
$currentThemeName  = $optionTheme->getOptionValue();
$currentMenuId     = $optionMenu->getOptionValue();
$optionLanguage    = ViewController::getViewData('optionLanguage');
$currentLanguage   = $optionLanguage->getOptionValue();
$listLanguages     = ViewController::getViewData('listLanguages');
?>
<div class="page-container">
    <div>
        <h1><?php echo __('Configuración'); ?></h1>
    </div>
    <div>
        <form class="form-horizontal" role="form" method="post">
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo __('Título del sitio'); ?></label>
                <div class="col-sm-10">
                    <input class="form-control" name="<?php echo $optionTitle->getOptionName(); ?>" value="<?php echo $optionTitle->getOptionValue(); ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo __('Descripción corta'); ?></label>
                <div class="col-sm-10">
                    <input class="form-control" name="<?php echo $optionDescription->getOptionName(); ?>" value="<?php echo $optionDescription->getOptionValue(); ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo __('Correo electrónico del administrador'); ?></label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" name="<?php echo $optionEmailAdmin->getOptionName(); ?>" value="<?php echo $optionEmailAdmin->getOptionValue(); ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo __('Dirección del sitio web'); ?></label>
                <div class="col-sm-10">
                    <input type="url" class="form-control" name="<?php echo $optionSiteUrl->getOptionName(); ?>" value="<?php echo $optionSiteUrl->getOptionValue(); ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo __('Menu actual'); ?></label>
                <div class="col-sm-10">
                    <select class="form-control" name="<?php echo $optionMenu->getOptionName(); ?>">
                        <?php array_walk($menuList, function(Menu $menu) use ($currentMenuId) {
                            $selected = '';
                            $menuId   = $menu->getId();
    
                            if ($menuId == $currentMenuId) {
                                $selected = 'selected';
                            }
    
                            echo "<option value='$menuId' $selected>" . $menu->getMenuTitle() . '</option>';
                        }); ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo __('Datos a mostrar por pagina'); ?></label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" name="<?php echo $optionPaged->getOptionName(); ?>" min="1" value="<?php echo $optionPaged->getOptionValue(); ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo __('Seleccionar plantilla'); ?></label>
                <div class="col-sm-10">
                    <select class="form-control" name="<?php echo $optionTheme->getOptionName(); ?>">
                        <?php array_walk($listThemes, function($themeName) use ($currentThemeName) {
                            $selected = '';
    
                            if ($themeName == $currentThemeName) {
                                $selected = 'selected';
                            }
    
                            echo "<option value='$themeName' $selected>$themeName</option>";
                        }); ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo __('Seleccionar idioma'); ?></label>
                <div class="col-sm-10">
                    <select class="form-control" name="<?php echo $optionLanguage->getOptionName(); ?>">
                        <option value="es">es</option>
                        <?php array_walk($listLanguages, function($language) use ($currentLanguage) {
                            $selected = '';
        
                            if ($language == $currentLanguage) {
                                $selected = 'selected';
                            }
        
                            echo "<option value='$language' $selected>$language</option>";
                        }); ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button class="btn btn-primary" name="update" value="update"><?php echo __('Guardar'); ?></button>
                </div>
            </div>
            <?php \SoftnCMS\util\Token::formField(); ?>
        </form>
    </div>
</div>
