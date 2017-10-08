<?php

use SoftnCMS\classes\constants\Constants;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\LicensesManager;
use SoftnCMS\models\managers\ProfilesLicensesManager;
use SoftnCMS\models\tables\Profile;
use SoftnCMS\util\Arrays;

$license            = ViewController::getViewData('license');
$title              = ViewController::getViewData('title');
$method             = ViewController::getViewData('method');
$isUpdate           = ViewController::getViewData('isUpdate');
$profiles           = ViewController::getViewData('profiles');
$selectedProfilesId = ViewController::getViewData('selectedProfilesId');
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
                    <input class="form-control" name="<?php echo LicensesManager::LICENSE_NAME; ?>" value="<?php echo $license->getLicenseName(); ?>">
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo __('Descripción'); ?></label>
                    <textarea class="form-control" name="<?php echo LicensesManager::LICENSE_DESCRIPTION; ?>" rows="5"><?php echo $license->getLicenseDescription(); ?></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo __('Perfiles'); ?></label>
                    <select class="form-control" name="<?php echo ProfilesLicensesManager::PROFILE_ID; ?>[]" multiple>
                        <?php array_walk($profiles, function(Profile $profile) use ($selectedProfilesId) {
                            $profileId = $profile->getId();
                            $selected  = Arrays::valueExists($selectedProfilesId, $profileId) ? 'selected' : '';
                            echo "<option value='$profileId' $selected>" . $profile->getProfileName() . '</option>';
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
            <input type="hidden" name="<?php echo LicensesManager::COLUMN_ID; ?>" value="<?php echo $license->getId(); ?>"/>
            <?php \SoftnCMS\util\Token::formField(); ?>
        </form>
    </div>
</div>
