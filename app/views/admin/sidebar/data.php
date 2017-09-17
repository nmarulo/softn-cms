<?php

use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\tables\Sidebar;

$sidebars             = ViewController::getViewData('sidebars');
$siteUrlUpdate        = \SoftnCMS\rute\Router::getSiteURL() . 'admin/sidebar/update/';
$strTranslateTitle    = __('Título');
$strTranslatePosition = __('Posición');
?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th></th>
                <th><?php echo $strTranslateTitle; ?></th>
                <th><?php echo $strTranslatePosition; ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th></th>
                <th><?php echo $strTranslateTitle; ?></th>
                <th><?php echo $strTranslatePosition; ?></th>
            </tr>
        </tfoot>
        <tbody>
        <?php array_walk($sidebars, function(Sidebar $sidebar) use ($siteUrlUpdate) { ?>
            <tr>
                <td class="options">
                    <a class="btn-action-sm btn btn-primary" href="<?php echo $siteUrlUpdate . $sidebar->getId(); ?>" title="Editar"><span class="glyphicon glyphicon-edit"></span></a>
                    <button class="btn-action-sm btn btn-danger" data-id="<?php echo $sidebar->getId(); ?>" title="Borrar"><span class="glyphicon glyphicon-remove-sign"></span></button>
                </td>
                <td><?php echo $sidebar->getSidebarTitle(); ?></td>
                <td><?php echo $sidebar->getSidebarPosition(); ?></td>
            </tr>
        <?php }); ?>
        </tbody>
    </table>
</div>
