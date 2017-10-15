<?php

use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\MenusManager;
use SoftnCMS\models\tables\Menu;

$siteUrl       = ViewController::getViewData('siteUrl');
$siteUrlUpdate = $siteUrl . 'admin/menu/update/';
$siteUrlCreate = $siteUrl . 'admin/menu/create/?parentMenu=';
$subMenu       = ViewController::getViewData('subMenu');
$subMenuId     = $subMenu->getId();
?>
<li>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="clearfix">
                <div class="pull-left">
                    <a class="btn btn-success" href="<?php echo $siteUrlCreate . $subMenuId; ?>" title="Agregar">
                        <span class="glyphicon glyphicon-plus"></span>
                    </a>
                    <a class='btn btn-primary' href='<?php echo $siteUrlUpdate . $subMenuId; ?>' title='Editar'>
                        <span class='glyphicon glyphicon-edit'></span>
                    </a>
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-data-delete" data-id="<?php echo $subMenuId; ?>" title="Borrar">
                        <span class="glyphicon glyphicon-remove-sign"></span>
                    </button>
                    <?php echo $subMenu->getMenuTitle(); ?>
                </div>
                <!--                <div class="pull-right">-->
                <!--                    <ul class="list-inline">-->
                <!--                        <li>-->
                <!--                            <button class="btn btn-success">-->
                <!--                                <span class="glyphicon glyphicon-arrow-up"></span>-->
                <!--                            </button>-->
                <!--                        </li>-->
                <!--                        <li>-->
                <!--                            <button class="btn btn-danger">-->
                <!--                                <span class="glyphicon glyphicon-arrow-down"></span>-->
                <!--                            </button>-->
                <!--                        </li>-->
                <!--                        <li>-->
                <!--                            <span class="badge">--><?php //echo $subMenu->getMenuPosition(); ?><!--</span>-->
                <!--                        </li>-->
                <!--                        <li>-->
                <!--                            <button class="btn btn-success">-->
                <!--                                <span class="glyphicon glyphicon-arrow-up"></span>-->
                <!--                                <span class="glyphicon glyphicon-arrow-up"></span>-->
                <!--                            </button>-->
                <!--                        </li>-->
                <!--                        <li>-->
                <!--                            <button class="btn btn-danger">-->
                <!--                                <span class="glyphicon glyphicon-arrow-down"></span>-->
                <!--                                <span class="glyphicon glyphicon-arrow-down"></span>-->
                <!--                            </button>-->
                <!--                        </li>-->
                <!--                    </ul>-->
                <!--                </div>-->
            </div>
        </div>
    </div>
    <?php if ($subMenu->getMenuTotalChildren() > 0) { ?>
        <ul class="list-unstyled">
            <?php
            $menusManager = new MenusManager();
            $menus        = $menusManager->searchByMenuSub($subMenu->getId());

            array_walk($menus, function(Menu $menu) {
                ViewController::sendViewData('subMenu', $menu);
                ViewController::singleView('dataparentmenu');
            });
            ?>
        </ul>
    <?php } ?>
</li>
