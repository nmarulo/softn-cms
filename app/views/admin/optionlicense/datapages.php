<?php

use SoftnCMS\classes\constants\Constants;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\util\Arrays;

$optionLicenses    = ViewController::getViewData('optionLicenses');
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
$isUpdate          = ViewController::getViewData('isUpdate');

if (!empty($optionLicenses)) {
    $checkedInsert = empty(Arrays::get($optionLicenses, 'insert')) ? '' : 'checked';
    $checkedUpdate = empty(Arrays::get($optionLicenses, 'update')) ? '' : 'checked';
    $checkedDelete = empty(Arrays::get($optionLicenses, 'delete')) ? '' : 'checked';
}
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" href="#<?php echo $strCollapseId; ?>" class="clearfix">
                <span class="pull-left"><?php echo $className; ?></span>
                <span class="pull-right"><span class="glyphicon glyphicon-chevron-down"></span></span>
            </a>
        </h4>
    </div>
    <div id="<?php echo $strCollapseId; ?>" class="collapse">
        <div class="panel-body">
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
                <?php array_walk($controllerMethods, function($method) use ($managerConstants, $className, $optionLicenses) {
                    $classNameMethod         = $className . '_' . $method;
                    $inputNameClassMethodId  = $classNameMethod . '_ID';
                    $fields                  = [];
                    $inputValueClassMethodId = NULL;
    
                    if (!empty($optionLicenses)) {
                        $data = Arrays::get($optionLicenses, $method);
        
                        if (!empty($data)) {
                            $fields                  = Arrays::get($data, 'fields');
                            $object                  = Arrays::get($data, 'object');
                            $inputValueClassMethodId = $object->getId();
                        }
                    } ?>
                    <div class="panel panel-default form-input-checkboxes">
                        <div class="panel-heading" role="tab" id="heading-<?php echo $classNameMethod; ?>">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion-<?php echo $className; ?>" href="#collapse-<?php echo $classNameMethod; ?>" aria-controls="collapse-<?php echo $classNameMethod; ?>">
                                    <?php echo $method; ?>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse-<?php echo $classNameMethod; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-<?php echo $classNameMethod; ?>">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-1">
                                        <button class="btn btn-primary btn-check-all btn-action-sm" type="button">
                                            <span class="glyphicon glyphicon-ok"></span>
                                        </button>
                                        <button class="btn btn-danger btn-uncheck-all btn-action-sm" type="button">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </button>
                                    </div>
                                    <div class="col-sm-11">
                                        <div class="row">
                                            <?php array_walk($managerConstants, function($value) use ($className, $method, $fields) {
                                                $inputName     = sprintf('%1$s_%2$s_%3$s', $className, $method, $value);
                                                $checkedColumn = Arrays::valueExists($fields, $value) ? 'checked' : ''; ?>
                                                <div class="col-sm-6">
                                                    <label>
                                                        <input type="checkbox" name="<?php echo $inputName; ?>" <?php echo $checkedColumn; ?>>
                                                        <?php echo $value; ?>
                                                    </label>
                                                </div>
                                            <?php }); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="<?php echo $inputNameClassMethodId; ?>" value="<?php echo $inputValueClassMethodId; ?>"/>
                <?php }); ?>
            </div>
            <?php if ($isUpdate) { ?>
                <button class="btn btn-primary" name="<?php echo Constants::FORM_UPDATE; ?>" value="<?php echo Constants::FORM_UPDATE; ?>"><?php echo __('Actualizar'); ?></button>
            <?php } else { ?>
                <button class="btn btn-primary" name="<?php echo Constants::FORM_CREATE; ?>" value="<?php echo Constants::FORM_CREATE; ?>"><?php echo __('Publicar'); ?></button>
            <?php } ?>
        </div>
    </div>
</div>
