<?php

use SoftnCMS\controllers\ViewController;

$controllerName = ViewController::getViewData('controllerName');
$methods        = ViewController::getViewData('methods');
$strCollapseId  = 'collapse' . $controllerName;
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="pull-left">
            <?php echo $controllerName; ?>
        </div>
        <div class="pull-right">
            <a data-toggle="collapse" href="#<?php echo $strCollapseId; ?>">
              <span class="glyphicon glyphicon-chevron-down"></span>
            </a>
        </div>
    </div>
    <div id="<?php echo $strCollapseId; ?>" class="panel-body collapse">
        <div class="list-group">
            <?php array_walk($methods, function($method) use ($controllerName) {
                $controllerMethod = strtolower($controllerName . $method);
                $optionRead = $controllerMethod . LICENSE_READ;
                $optionUpdate = $controllerMethod . LICENSE_UPDATE;
                $optionInsert = $controllerMethod . LICENSE_INSERT;
                $optionDelete = $controllerMethod . LICENSE_DELETE;
                ?>
                <div class="list-group-item">
                    <h4 class="list-group-item-heading"><?php echo $method; ?></h4>
                    <div class="list-group-item-text">
                        <label class="checkbox-inline">
                            <input type="checkbox" name="<?php echo $optionRead; ?>" value="option2">
                            <?php echo __('Leer'); ?>
                        </label>
                        <label class="radio-inline">
                            <input type="checkbox" name="<?php echo $optionUpdate; ?>" value="option2">
                            <?php echo __('Crear'); ?>
                        </label>
                        <label class="radio-inline">
                            <input type="checkbox" name="<?php echo $optionInsert; ?>" value="option2">
                            <?php echo __('Actualizar'); ?>
                        </label>
                        <label class="radio-inline">
                            <input type="checkbox" name="<?php echo $optionDelete; ?>" value="option2">
                            <?php echo __('Borrar'); ?>
                        </label>
                    </div>
                </div>
            <?php }); ?>
        </div>
    </div>
</div>
