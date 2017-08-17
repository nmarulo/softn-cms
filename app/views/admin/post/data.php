<?php

use SoftnCMS\models\tables\Post;
use SoftnCMS\controllers\ViewController;

$posts                = ViewController::getViewData('posts');
$siteUrlUpdate        = \SoftnCMS\rute\Router::getSiteURL() . 'admin/post/update/';
$strTranslateTitle    = __('Título');
$strTranslateAuthor   = __('Autor');
$strTranslateComments = __('Comentarios');
$strTranslateDate     = __('Fecha');
$strTranslateState    = __('Estado');
ViewController::singleViewByDirectory('pagination'); ?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th></th>
                <th><?php echo $strTranslateTitle; ?></th>
                <th><?php echo $strTranslateAuthor; ?></th>
                <th><?php echo $strTranslateComments; ?></th>
                <th><?php echo $strTranslateDate; ?></th>
                <th><?php echo $strTranslateState; ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th></th>
                <th><?php echo $strTranslateTitle; ?></th>
                <th><?php echo $strTranslateAuthor; ?></th>
                <th><?php echo $strTranslateComments; ?></th>
                <th><?php echo $strTranslateDate; ?></th>
                <th><?php echo $strTranslateState; ?></th>
            </tr>
        </tfoot>
        <tbody>
        <?php array_walk($posts, function(Post $post) use ($siteUrlUpdate) { ?>
            <tr>
                <td class="options">
                    <a class="btn-action-sm btn btn-primary" href="<?php echo $siteUrlUpdate . $post->getId(); ?>" title="Editar"><span class="glyphicon glyphicon-edit"></span></a>
                    <button class="btn-action-sm btn btn-danger" data-id="<?php echo $post->getId(); ?>" title="Borrar"><span class="glyphicon glyphicon-remove-sign"></span></button>
                </td>
                <td><a href="#" target="_blank"><?php echo $post->getPostTitle(); ?></a></td>
                <td><a href="#" target="_blank"><?php echo $post->getUserId(); ?></a></td>
                <td><span class="badge"><?php echo $post->getPostCommentCount(); ?></span></td>
                <td><?php echo $post->getPostDate(); ?></td>
                <td><?php echo $post->getPostStatus(); ?></td>
            </tr>
        <?php }); ?>
        </tbody>
    </table>
</div>
<?php ViewController::singleViewByDirectory('pagination');
