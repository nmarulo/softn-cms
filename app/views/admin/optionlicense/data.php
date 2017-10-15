<?php

use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\tables\License;

$licenses      = ViewController::getViewData('licenses');
$siteUrlUpdate = ViewController::getViewData('siteUrl') . 'admin/optionlicense/update/';
ViewController::singleViewByDirectory('pagination'); ?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th></th>
                <th>License</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th></th>
                <th>License</th>
            </tr>
        </tfoot>
        <tbody>
        <?php array_walk($licenses, function(License $license) use ($siteUrlUpdate) {
            $id = $license->getId(); ?>
            <tr>
                <td class="options">
                    <a class="btn-action-sm btn btn-primary" href="<?php echo $siteUrlUpdate . $id; ?>" title="Editar"><span class="glyphicon glyphicon-edit"></span></a>
                    <button type="button" class="btn-action-sm btn btn-danger" data-toggle="modal" data-target="#modal-data-delete" data-id="<?php echo $license->getId(); ?>" title="Borrar">
                        <span class="glyphicon glyphicon-remove-sign"></span>
                    </button>
                </td>
                <td><?php echo $license->getLicenseName(); ?></td>
            </tr>
        <?php }); ?>
        </tbody>
    </table>
</div>
<?php ViewController::singleViewByDirectory('pagination');
