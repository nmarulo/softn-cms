<?php

use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\util\Arrays;

$pageLicense       = ViewController::getViewData('pageLicense');
$className         = ViewController::getViewData('className');
$controllerMethods = ViewController::getViewData('controllerMethods');
$managerConstants  = ViewController::getViewData('managerConstants');
$strCollapseId     = 'collapse-' . $className;
$inputNameUpdate   = $className . '_UPDATE';
$inputNameInsert   = $className . '_INSERT';
$inputNameDelete   = $className . '_DELETE';
$checkedInsert     = '';
$checkedUpdate     = '';
$checkedDelete     = '';
$method            = ViewController::getViewData('method');
$isUpdate          = $method == CRUDManagerAbstract::FORM_UPDATE;

if (!empty($pageLicense)) {
    $checkedInsert = $pageLicense->isCanInsert() ? 'checked' : '';
    $checkedUpdate = $pageLicense->isCanUpdate() ? 'checked' : '';
    $checkedDelete = $pageLicense->isCanDelete() ? 'checked' : '';
}
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="pull-left"><?php echo $className; ?></div>
        <div class="pull-right">
            <a data-toggle="collapse" href="#<?php echo $strCollapseId; ?>">
                <span class="glyphicon glyphicon-chevron-down"></span>
            </a>
        </div>
    </div>
    <div id="<?php echo $strCollapseId; ?>" class="panel-body collapse">
        <div class="list-group">
            <div class="list-group-item">
                <div class="list-group-item-text">
                    <label class="radio-inline">
                        <input type="checkbox" name="<?php echo $inputNameInsert; ?>" <?php echo $checkedInsert; ?>>
                        <?php echo __('Crear'); ?>
                    </label>
                    <label class="radio-inline">
                        <input type="checkbox" name="<?php echo $inputNameUpdate; ?>" <?php echo $checkedUpdate; ?>>
                        <?php echo __('Actualizar'); ?>
                    </label>
                    <label class="radio-inline">
                        <input type="checkbox" name="<?php echo $inputNameDelete; ?>" <?php echo $checkedDelete; ?>>
                        <?php echo __('Borrar'); ?>
                    </label>
                </div>
            </div>
        </div>
        <h3><?php echo __('Campos a visualizar por pagina'); ?></h3>
        <div class="panel-group" id="accordion-<?php echo $className; ?>" role="tablist">
            <?php array_walk($controllerMethods, function($method) use ($managerConstants, $className, $pageLicense) {
                $classNameMethod = $className . '_' . $method;
                $fields          = [];
    
                if (!empty($pageLicense)) {
                    $methodLicenses = $pageLicense->getMethods();
                    $methodLicense  = Arrays::get($methodLicenses, $method);
                    $fields         = empty($methodLicense) ? [] : $methodLicense->getFields();
                } ?>
                <div class="panel panel-default">
                    <div class="panel-heading clearfix" role="tab" id="heading-<?php echo $classNameMethod; ?>">
                            <a data-toggle="collapse" data-parent="#accordion-<?php echo $className; ?>" href="#collapse-<?php echo $classNameMethod; ?>" aria-controls="collapse-<?php echo $classNameMethod; ?>">
                                <h4 class="panel-title"><?php echo $method; ?></h4>
                            </a>
                    </div>
                    <div id="collapse-<?php echo $classNameMethod; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-<?php echo $classNameMethod; ?>">
                        <div class="panel-body">
                            <?php array_walk($managerConstants, function($value) use ($className, $method, $fields) {
                                $inputName     = sprintf('%1$s_%2$s_%3$s', $className, $method, $value);
                                $checkedColumn = Arrays::valueExists($fields, $value) ? 'checked' : ''; ?>
                                <div class="checkbox-inline">
                                    <label>
                                        <input type="checkbox" name="<?php echo $inputName; ?>" <?php echo $checkedColumn; ?>>
                                        <?php echo $value; ?>
                                    </label>
                                </div>
                            <?php }); ?>
                        </div>
                    </div>
                </div>
            <?php }); ?>
        </div>
        <?php if ($isUpdate) { ?>
            <button class="btn btn-primary" name="<?php echo CRUDManagerAbstract::FORM_UPDATE; ?>" value="<?php echo CRUDManagerAbstract::FORM_UPDATE; ?>"><?php echo __('Actualizar'); ?></button>
        <?php } else { ?>
            <button class="btn btn-primary" name="<?php echo CRUDManagerAbstract::FORM_CREATE; ?>" value="<?php echo CRUDManagerAbstract::FORM_CREATE; ?>"><?php echo __('Publicar'); ?></button>
        <?php } ?>
    </div>
</div>
