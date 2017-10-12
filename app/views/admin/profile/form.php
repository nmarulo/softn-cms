<?php

use SoftnCMS\classes\constants\Constants;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\ProfilesLicensesManager;
use SoftnCMS\models\managers\ProfilesManager;
use SoftnCMS\models\tables\License;
use SoftnCMS\util\Arrays;

$profile            = ViewController::getViewData('profile');
$title              = ViewController::getViewData('title');
$method             = ViewController::getViewData('method');
$isUpdate           = ViewController::getViewData('isUpdate');
$licenses           = ViewController::getViewData('licenses');
$selectedLicensesId = ViewController::getViewData('selectedLicensesId');
?>
<div class="page-container" data-menu-collapse-id="user">
    <div>
        <h1><?php echo $title; ?></h1>
    </div>
    <div>
        <form method="post">
            <div id="content-left" class="col-sm-9">
                <div class="form-group">
                    <label class="control-label"><?php echo __('Nombre'); ?></label>
                    <input class="form-control" name="<?php echo ProfilesManager::PROFILE_NAME; ?>" value="<?php echo $profile->getProfileName(); ?>">
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo __('Descripción'); ?></label>
                    <textarea class="form-control" name="<?php echo ProfilesManager::PROFILE_DESCRIPTION; ?>" rows="5"><?php echo $profile->getProfileDescription(); ?></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo __('Permisos'); ?></label>
                    <select class="form-control" name="<?php echo ProfilesLicensesManager::LICENSE_ID; ?>[]" multiple>
                        <?php array_walk($licenses, function(License $license) use ($selectedLicensesId) {
                            $licenseId = $license->getId();
                            $selected  = Arrays::valueExists($selectedLicensesId, $licenseId) ? 'selected' : '';
                            echo "<option value='$licenseId' $selected>" . $license->getLicenseName() . '</option>';
                        }); ?>
                    </select>
                </div>
            </div>
            <div id="content-right" class="col-sm-3">
                <div class="panel panel-default">
                    <div class="panel-heading"><?php echo __('Publicación'); ?></div>
                    <div class="panel-body">
                        <?php if ($isUpdate) { ?>
                            <button class="btn btn-primary btn-block" name="<?php echo Constants::FORM_UPDATE; ?>" value="<?php echo Constants::FORM_UPDATE; ?>"><?php echo __('Actualizar'); ?></button>
                        <?php } else { ?>
                            <button class="btn btn-primary btn-block" name="<?php echo Constants::FORM_CREATE; ?>" value="<?php echo Constants::FORM_CREATE; ?>"><?php echo __('Publicar'); ?></button>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <input type="hidden" name="<?php echo ProfilesManager::COLUMN_ID; ?>" value="<?php echo $profile->getId(); ?>"/>
            <?php \SoftnCMS\util\Token::formField(); ?>
        </form>
    </div>
</div>
