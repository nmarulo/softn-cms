<?php

use SoftnCMS\models\tables\Comment;
use SoftnCMS\models\tables\Post;
use SoftnCMS\controllers\ViewController;

//ViewController::registerScript('api-github');
$countPosts           = ViewController::getViewData('countPosts');
$countPages           = ViewController::getViewData('countPages');
$countComments        = ViewController::getViewData('countComments');
$countCategories      = ViewController::getViewData('countCategories');
$countTerms           = ViewController::getViewData('countTerms');
$countUsers           = ViewController::getViewData('countUsers');
$posts                = ViewController::getViewData('posts');
$comments             = ViewController::getViewData('comments');
$siteUrl              = ViewController::getViewData('siteUrl') . "admin/index/";
$strTranslatePosts    = __('Entradas');
$strTranslateComments = __('Comentarios');
?>
<div class="page-container" data-url="<?php echo $siteUrl; ?>">
    <div>
        <h1><?php echo __('Información general'); ?></h1>
    </div>
    <div class="row clearfix">
        <div class="col-sm-6">
            <div class="panel panel-primary">
                <div class="panel-heading"><?php echo __('Estadísticas Generales'); ?></div>
                <div class="panel-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <span class="badge"><?php echo $countPosts; ?></span>
                            <span><?php echo $strTranslatePosts; ?></span>
                        </li>
                        <li class="list-group-item">
                            <span class="badge"><?php echo $countPages; ?></span>
                            <span><?php echo __('Paginas'); ?></span>
                        </li>
                        <li class="list-group-item">
                            <span class="badge"><?php echo $countComments; ?></span>
                            <span><?php echo $strTranslateComments; ?></span>
                        </li>
                        <li class="list-group-item">
                            <span class="badge"><?php echo $countCategories; ?></span>
                            <span><?php echo __('Categorías'); ?></span>
                        </li>
                        <li class="list-group-item">
                            <span class="badge"><?php echo $countTerms; ?></span>
                            <span><?php echo __('Etiquetas'); ?></span>
                        </li>
                        <li class="list-group-item">
                            <span class="badge"><?php echo $countUsers; ?></span>
                            <span><?php echo __('Usuarios'); ?></span>
                        </li>
                    </ul>
                </div>
            </div>
            <div id="data-github"></div>
        </div>
        <div class="col-sm-6">
            <div class="panel panel-primary">
                <div class="panel-heading"><?php echo $strTranslatePosts; ?></div>
                <div class="panel-body">
                    <ul class="list-group">
                        <?php
                        array_walk($posts, function(Post $post) { ?>
                            <li class="list-group-item clearfix">
                                <span class="pull-left"><?php echo $post->getPostDate(); ?></span>
                                <span><?php echo $post->getPostTitle(); ?></span>
                            </li>
                        <?php }); ?>
                    </ul>
                </div>
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading"><?php echo $strTranslateComments; ?></div>
                <div class="panel-body">
                    <ul class="list-group">
                        <?php array_walk($comments, function(Comment $comment) { ?>
                            <li class="list-group-item clearfix">
                                <span class="pull-left"><?php echo $comment->getCommentDate(); ?></span>
                                <span><?php echo $comment->getCommentContents(); ?></span>
                            </li>
                        <?php }); ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
