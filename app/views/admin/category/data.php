<?php
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\OptionsManager;

$categories     = ViewController::getViewData('categories');
$optionsManager = new OptionsManager();
$siteUrl        = $optionsManager->getSiteUrl();
$siteUrlUpdate  = $siteUrl . 'admin/category/update/';
ViewController::singleViewDirectory('pagination'); ?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th></th>
                <th>Nombre</th>
                <th>Entradas</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th></th>
                <th>Nombre</th>
                <th>Entradas</th>
            </tr>
        </tfoot>
        <tbody>
        <?php foreach ($categories as $category) { ?>
            <tr>
                <td class="options">
                    <a class="btn-action-sm btn btn-primary" href="<?php echo $siteUrlUpdate . $category->getId(); ?>" title="Editar"><span class="glyphicon glyphicon-edit"></span></a>
                    <a class="btn-action-sm btn btn-danger" data-id="<?php echo $category->getId(); ?>" href="#" title="Borrar"><span class="glyphicon glyphicon-remove-sign"></span></a>
                </td>
                <td><?php echo $category->getCategoryName(); ?></td>
                <td><?php echo $category->getCategoryCount(); ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php ViewController::singleViewDirectory('pagination');
