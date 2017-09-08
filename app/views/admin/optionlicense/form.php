<?php

use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\OptionsLicensesManager;
use SoftnCMS\models\tables\License;
use SoftnCMS\util\Arrays;

$title           = ViewController::getViewData('title');
$optionsLicenses = ViewController::getViewData('optionsLicenses');
$dataList        = ViewController::getViewData('dataList');
$licenseSelected = ViewController::getViewData('license');
$licenses        = ViewController::getViewData('licenses');
$method          = ViewController::getViewData('method');
$isUpdate        = $method == OptionsLicensesManager::FORM_UPDATE;
?>
<div class="page-container" data-menu-collapse-id="option">
    <div>
        <h1><?php echo $title; ?></h1>
    </div>
    <div id="option-license">
        <form method="post" class="form-horizontal">
            <div class="form-group">
                <label class="control-label col-sm-2"><?php echo __('Permiso'); ?></label>
                <div class="col-sm-10">
                    <select class="form-control" name="<?php echo OptionsLicensesManager::LICENSE_ID ?>">
                        <?php
                        if (!empty($licenseSelected) && !empty($licenseSelected->getId())) {
                            echo "<option value='" . $licenseSelected->getId() . "' selected>" . $licenseSelected->getLicenseName() . '</option>';
                        }

                        $output = '';
                        array_walk($licenses, function(License $license) use (&$output) {
                            $output .= '<option value="' . $license->getId() . '">' . $license->getLicenseName() . '</option>';
                        });
                        echo $output;
                        ?>
                    </select>
                </div>
            </div>
            <?php array_walk($dataList, function($data) use ($optionsLicenses) {
                $className      = Arrays::get($data, 'className');
                $optionLicenses = Arrays::get($optionsLicenses, $className);
                ViewController::sendViewData('optionLicenses', $optionLicenses);
                ViewController::sendViewData('className', $className);
                ViewController::sendViewData('controllerMethods', Arrays::get($data, 'controllerMethods'));
                ViewController::sendViewData('managerConstants', Arrays::get($data, 'managerConstants'));
                ViewController::singleView('datapages');
            }); ?>
            <?php if ($isUpdate) { ?>
                <button class="btn btn-primary" name="<?php echo OptionsLicensesManager::FORM_UPDATE; ?>" value="<?php echo OptionsLicensesManager::FORM_UPDATE; ?>"><?php echo __('Actualizar'); ?></button>
            <?php } else { ?>
                <button class="btn btn-primary" name="<?php echo OptionsLicensesManager::FORM_CREATE; ?>" value="<?php echo OptionsLicensesManager::FORM_CREATE; ?>"><?php echo __('Publicar'); ?></button>
            <?php } ?>
            <input type="hidden" name="<?php echo OptionsLicensesManager::ID; ?>" value="<?php echo $licenseSelected->getId(); ?>"/>
        </form>
    </div>
</div>
