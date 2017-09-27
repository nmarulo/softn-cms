<?php

use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\tables\License;

$licenses         = ViewController::getViewData('licenses');
$siteUrlUpdate    = ViewController::getViewData('siteUrl') . 'admin/license/update/';
$strTranslateName = __('Nombre');
ViewController::singleViewByDirectory('pagination'); ?>
    <div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th></th>
                <th><?php echo $strTranslateName; ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th></th>
                <th><?php echo $strTranslateName; ?></th>
            </tr>
        </tfoot>
        <tbody>
        <?php array_walk($licenses, function(License $license) use ($siteUrlUpdate) { ?>
            <tr>
                <td class="options">
                    <a class="btn-action-sm btn btn-primary" href="<?php echo $siteUrlUpdate . $license->getId(); ?>" title="Editar"><span class="glyphicon glyphicon-edit"></span></a>
                    <button class="btn-action-sm btn btn-danger" data-id="<?php echo $license->getId(); ?>" title="Borrar"><span class="glyphicon glyphicon-remove-sign"></span></button>
                </td>
                <td><?php echo $license->getLicenseName(); ?></td>
            </tr>
        <?php }); ?>
        </tbody>
    </table>
</div>
<?php ViewController::singleViewByDirectory('pagination');
