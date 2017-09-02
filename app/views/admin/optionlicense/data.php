<?php

use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\tables\OptionLicense;

$optionLicenses = ViewController::getViewData('optionLicenses');
$siteUrlUpdate  = \SoftnCMS\rute\Router::getSiteURL() . 'admin/optionlicense/update/';
ViewController::singleViewByDirectory('pagination'); ?>
    <div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th></th>
                <th>id</th>
                <th>LicenseId</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th></th>
                <th>id</th>
                <th>LicenseId</th>
            </tr>
        </tfoot>
        <tbody>
        <?php array_walk($optionLicenses, function(OptionLicense $optionLicense) use ($siteUrlUpdate) {
            $id = $optionLicense->getId();
            $licenseId = $optionLicense->getLicenseId(); ?>
            <tr>
                <td class="options">
                    <a class="btn-action-sm btn btn-primary" href="<?php echo $siteUrlUpdate . $id . "?licenseId=$licenseId"; ?>" title="Editar"><span class="glyphicon glyphicon-edit"></span></a>
                    <button class="btn-action-sm btn btn-danger" data-id="<?php echo $id; ?>" title="Borrar"><span class="glyphicon glyphicon-remove-sign"></span></button>
                </td>
                <td><?php echo $id; ?></td>
                <td><?php echo $licenseId; ?></td>
            </tr>
        <?php }); ?>
        </tbody>
    </table>
</div>
<?php ViewController::singleViewByDirectory('pagination');
