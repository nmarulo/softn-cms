<?php

use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\tables\Menu;

$menus                = ViewController::getViewData('menus');
$siteUrl              = ViewController::getViewData('siteUrl');
$siteUrlUpdate        = $siteUrl . 'admin/menu/update/';
$siteUrlEdit          = $siteUrl . 'admin/menu/edit/';
$strTranslateTitle    = __('Título');
$strTranslateElements = __('Elementos');
ViewController::singleViewByDirectory('pagination'); ?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th></th>
                <th><?php echo $strTranslateTitle; ?></th>
                <th><?php echo $strTranslateElements; ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th></th>
                <th><?php echo $strTranslateTitle; ?></th>
                <th><?php echo $strTranslateElements; ?></th>
            </tr>
        </tfoot>
        <tbody>
        <?php array_walk($menus, function(Menu $menu) use ($siteUrlUpdate, $siteUrlEdit) { ?>
            <tr>
                <td class="options">
                    <a class="btn-action-sm btn btn-primary" href="<?php echo $siteUrlUpdate . $menu->getId(); ?>" title="Editar"><span class="glyphicon glyphicon-edit"></span></a>
                    <a class="btn-action-sm btn btn-warning" href="<?php echo $siteUrlEdit . $menu->getId(); ?>" title="Lista de submenus"><span class="glyphicon glyphicon-list"></span></a>
                    <button class="btn-action-sm btn btn-danger" data-id="<?php echo $menu->getId(); ?>" title="Borrar"><span class="glyphicon glyphicon-remove-sign"></span></button>
                </td>
                <td><a href="#" target="_blank"><?php echo $menu->getMenuTitle(); ?></a></td>
                <td><?php echo $menu->getMenuTotalChildren(); ?></td>
            </tr>
        <?php }); ?>
        </tbody>
    </table>
</div>
<?php ViewController::singleViewByDirectory('pagination');
