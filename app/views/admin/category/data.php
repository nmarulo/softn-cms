<?php

use SoftnCMS\models\tables\Category;
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
        <?php array_walk($categories, function(Category $category) use ($siteUrlUpdate) { ?>
            <tr>
                <td class="options">
                    <a class="btn-action-sm btn btn-primary" href="<?php echo $siteUrlUpdate . $category->getId(); ?>" title="Editar"><span class="glyphicon glyphicon-edit"></span></a>
                    <button class="btn-action-sm btn btn-danger" data-id="<?php echo $category->getId(); ?>" title="Borrar"><span class="glyphicon glyphicon-remove-sign"></span></button>
                </td>
                <td><?php echo $category->getCategoryName(); ?></td>
                <td><?php echo $category->getCategoryPostCount(); ?></td>
            </tr>
        <?php }); ?>
        </tbody>
    </table>
</div>
<?php ViewController::singleViewDirectory('pagination');
