<?php

use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\tables\Menu;
use SoftnCMS\models\tables\Profile;
use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\util\Gravatar;

$optionTitle                 = ViewController::getViewData('optionTitle');
$optionDescription           = ViewController::getViewData('optionDescription');
$optionPaged                 = ViewController::getViewData('optionPaged');
$optionSiteUrl               = ViewController::getViewData('optionSiteUrl');
$optionTheme                 = ViewController::getViewData('optionTheme');
$menuList                    = ViewController::getViewData('menuList');
$optionMenu                  = ViewController::getViewData('optionMenu');
$optionEmailAdmin            = ViewController::getViewData('optionEmailAdmin');
$listThemes                  = ViewController::getViewData('listThemes');
$currentThemeName            = $optionTheme->getOptionValue();
$currentMenuId               = $optionMenu->getOptionValue();
$optionLanguage              = ViewController::getViewData('optionLanguage');
$currentLanguage             = $optionLanguage->getOptionValue();
$listLanguages               = ViewController::getViewData('listLanguages');
$optionDefaultProfile        = ViewController::getViewData('optionDefaultProfile');
$profilesList                = ViewController::getViewData('profilesList');
$currentProfileId            = $optionDefaultProfile->getOptionValue();
$gravatar                    = ViewController::getViewData('gravatar');
$gravatarSizeList            = ViewController::getViewData('gravatarSizeList');
$gravatarDefaultImageList    = ViewController::getViewData('gravatarDefaultImageList');
$gravatarRatingList          = ViewController::getViewData('gravatarRatingList');
$gravatarCheckedForceDefault = $gravatar->getForceDefault() ? 'checked' : '';
?>
<div class="page-container" data-menu-collapse-id="option">
    <div>
        <h1><?php echo __('Configuración'); ?></h1>
    </div>
    <div>
        <form class="form-horizontal" method="post">
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
                <label class="col-sm-2 control-label"><?php echo __('Perfil por defecto'); ?></label>
                <div class="col-sm-10">
                    <select class="form-control" name="<?php echo $optionDefaultProfile->getOptionName(); ?>">
                        <?php array_walk($profilesList, function(Profile $profile) use ($currentProfileId) {
                            $selected = '';
                            $id       = $profile->getId();
    
                            if ($id == $currentProfileId) {
                                $selected = 'selected';
                            }
    
                            echo "<option value='$id' $selected>" . $profile->getProfileName() . '</option>';
                        }); ?>
                    </select>
                </div>
            </div>
            <h3>Gravatar</h3>
            <div class="form-group">
                <div class="col-sm-2 control-label"><?php echo __('Tamaño'); ?></div>
                <div class="col-sm-10">
                    <?php array_walk($gravatarSizeList, function($size) use ($gravatar) {
                        $checked = $gravatar->getSize() == $size ? 'checked' : '';
                        ?>
                        <label class="radio-inline">
                            <input type="radio" name="<?php echo OptionsManager::OPTION_GRAVATAR_SIZE; ?>" value="<?php echo $size; ?>" <?php echo $checked; ?>/>
                            <?php echo $size; ?>
                        </label>
                    <?php }); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-2 control-label"><?php echo __('Imagen por defecto'); ?></div>
                <div class="col-sm-10">
                    <?php array_walk($gravatarDefaultImageList, function($defaultImage) use ($gravatar) {
                        $checked  = $gravatar->getDefaultImage() == $defaultImage ? 'checked' : '';
                        $srcImage = Gravatar::URL . "?d=$defaultImage";
                        ?>
                        <label class="radio-inline">
                            <input type="radio" name="<?php echo OptionsManager::OPTION_GRAVATAR_DEFAULT_IMAGE; ?>" value="<?php echo $defaultImage; ?>" <?php echo $checked; ?>/>
                            <img src="<?php echo $srcImage; ?>"/>
                        </label>
                    <?php }); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-2 control-label"><?php echo __('Calificación'); ?></div>
                <div class="col-sm-10">
                    <?php array_walk($gravatarRatingList, function($rating) use ($gravatar) {
                        $checked = $gravatar->getRating() == $rating ? 'checked' : '';
                        ?>
                        <label class="radio-inline">
                            <input type="radio" name="<?php echo OptionsManager::OPTION_GRAVATAR_RATING; ?>" value="<?php echo $rating; ?>" <?php echo $checked; ?>/>
                            <?php echo $rating; ?>
                        </label>
                    <?php }); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-2 control-label"><?php echo __('Forzar imagen por defecto'); ?></div>
                <div class="col-sm-10">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="<?php echo OptionsManager::OPTION_GRAVATAR_FORCE_DEFAULT; ?>" <?php echo $gravatarCheckedForceDefault; ?>/>
                    </label>
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
