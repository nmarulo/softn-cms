<?php

use SoftnCMS\controllers\ViewController;

$master                 = ViewController::getViewData('master');
$develop                = ViewController::getViewData('develop');
$lastUpdateDevelop      = ViewController::getViewData('lastUpdateDevelop');
$lastUpdateMaster       = ViewController::getViewData('lastUpdateMaster');
$strTranslateLastUpdate = __('Ultima actualización');
?>
<div class="panel panel-primary">
    <div class="panel-heading"><?php echo __('Novedades'); ?></div>
    <div class="panel-body">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"><a href="#master" aria-controls="master" role="tab" data-toggle="tab">Master</a></li>
            <li role="presentation" class="active"><a href="#develop" aria-controls="develop" role="tab" data-toggle="tab">Develop</a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane" id="master">
                <p><?php echo $strTranslateLastUpdate; ?>: <?php echo $lastUpdateMaster; ?>
                    <a href="https://github.com/nmarulo/softn-cms" target="_blank">GitHub</a></p>
                <ul class="list-group">
                    <?php array_walk($master, function($value) { ?>
                        <li class="list-group-item">
                            <a href="<?php echo $value['authorUrl']; ?>" target="_blank"><span class="label label-success"><?php echo $value['author']; ?></span></a>
                            <a href="<?php echo $value['commitUrl']; ?>" target="_blank"><?php echo $value['commitTitle']; ?></a>
                        </li>
                    <?php }); ?>
                </ul>
            </div>
            <div role="tabpanel" class="tab-pane active" id="develop">
                <p><?php echo $strTranslateLastUpdate; ?>: <?php echo $lastUpdateDevelop; ?>
                    <a href="https://github.com/nmarulo/softn-cms" target="_blank">GitHub</a></p>
                <ul class="list-group">
                    <?php array_walk($develop, function($value) { ?>
                        <li class="list-group-item">
                            <a href="<?php echo $value['authorUrl']; ?>" target="_blank"><span class="label label-success"><?php echo $value['author']; ?></span></a>
                            <a href="<?php echo $value['commitUrl']; ?>" target="_blank"><?php echo $value['commitTitle']; ?></a>
                        </li>
                    <?php }); ?>
                </ul>
            </div>
        </div>
    </div>
</div>
