<?php
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\OptionsManager;

$posts          = ViewController::getViewData('posts');
$optionsManager = new OptionsManager();
$siteUrl        = $optionsManager->getSiteUrl();

ViewController::singleViewDirectory('pagination'); ?>
    <div id="contentPost" class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th></th>
                <th>Título</th>
                <th>Autor</th>
                <th>Comentarios</th>
                <th>Fecha</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th></th>
                <th>Título</th>
                <th>Autor</th>
                <th>Comentarios</th>
                <th>Fecha</th>
                <th>Estado</th>
            </tr>
        </tfoot>
        <tbody>
        <?php foreach ($posts as $post) { ?>
            <tr>
                <td class="options">
                    <a class="btn-action-sm btn btn-primary" href="<?php echo $siteUrl . 'admin/post/update/' . $post->getId(); ?>" title="Editar"><span class="glyphicon glyphicon-edit"></span></a>
                    <a class="btn-action-sm btn btn-danger" data-id="<?php echo $post->getId(); ?>" href="#" title="Borrar"><span class="glyphicon glyphicon-remove-sign"></span></a>
                </td>
                <td><a href="#" target="_blank"><?php echo $post->getPostTitle(); ?></a></td>
                <td><a href="#" target="_blank"><?php echo $post->getUserID(); ?></a></td>
                <td><span class="badge"><?php echo $post->getCommentCount(); ?></span></td>
                <td><?php echo $post->getPostDate(); ?></td>
                <td><?php echo $post->getPostStatus(); ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php ViewController::singleViewDirectory('pagination');
