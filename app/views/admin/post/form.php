<?php

use SoftnCMS\classes\constants\Constants;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\PostsCategoriesManager;
use SoftnCMS\models\managers\PostsManager;
use SoftnCMS\models\managers\PostsTermsManager;
use SoftnCMS\models\tables\Category;
use SoftnCMS\models\tables\Term;
use SoftnCMS\models\tables\User;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\HTML;

ViewController::registerScript('form');
$title                = ViewController::getViewData('title');
$post                 = ViewController::getViewData('post');
$categories           = ViewController::getViewData('categories');
$terms                = ViewController::getViewData('terms');
$selectedCategoriesId = ViewController::getViewData('selectedCategoriesId');
$selectedTermsId      = ViewController::getViewData('selectedTermsId');
$method               = ViewController::getViewData('method');
$isUpdate             = ViewController::getViewData('isUpdate');
$linkPost             = ViewController::getViewData('linkPost');
$usersList            = ViewController::getViewData('usersList');
$selectedUserId       = ViewController::getViewData('selectedUserId');
?>
<div class="page-container" data-menu-collapse-id="post">
    <div>
        <h1><?php echo $title; ?></h1>
    </div>
    <div>
        <div class="row clearfix">
            <form method="post">
                <div class="col-sm-9">
                    <div class="form-group">
                        <input class="form-control input-lg" name="<?php echo PostsManager::POST_TITLE; ?>" value="<?php echo $post->getPostTitle(); ?>">
                    </div>
                    <?php if ($isUpdate) { ?>
                        <div class="form-group">
                            <label><?php echo __('Enlace'); ?>: <a href="<?php echo $linkPost; ?>" target="_blank"><?php echo $linkPost; ?></a></label>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label class="control-label"><?php echo __('Contenido'); ?></label>
                        <textarea id="textContent" class="form-control" name="<?php echo PostsManager::POST_CONTENTS; ?>" rows="5"><?php echo $post->getPostContents(); ?></textarea>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="checkbox">
                                <label>
                                    <input name="<?php echo PostsManager::POST_COMMENT_STATUS; ?>" type="checkbox" <?php echo $post->getPostCommentStatus() ? 'checked' : ''; ?>> <?php echo __('Habilitar comentarios'); ?>
                                </label>
                            </div>
                            <div class="form-group">
                                <?php
                                $options = HTML::createSelectOption($usersList, function(User $user) {
                                    return [
                                        'optionValue' => $user->getId(),
                                        'optionText'  => $user->getUserName(),
                                    ];
                                }, $selectedUserId);
                                HTML::selectOne(PostsManager::USER_ID, $options, [
                                    'class'     => 'form-control',
                                    'labelText' => __('Autor'),
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="content-right" class="col-sm-3">
                    <div class="panel panel-default">
                        <div class="panel-heading"><?php echo __('Publicación'); ?></div>
                        <div class="panel-body">
                            <div class="form-group checkbox">
                                <label>
                                    <input name="<?php echo PostsManager::POST_STATUS; ?>" type="checkbox" <?php echo $post->getPostStatus() ? 'checked' : ''; ?>> <?php echo __('Visible'); ?>
                                </label>
                            </div>
                            <?php if ($isUpdate) { ?>
                                <p><?php echo __('Ultima actualización'); ?>: <span class="label label-warning">
                                        <span class="glyphicon glyphicon-time"></span>
                                        <?php echo $post->getPostUpdate(); ?></span>
                                </p>
                                <button class="btn btn-primary btn-block" name="<?php echo Constants::FORM_UPDATE; ?>" value="<?php echo Constants::FORM_UPDATE; ?>"><?php echo __('Actualizar'); ?></button>
                            <?php } else { ?>
                                <button class="btn btn-primary btn-block" name="<?php echo Constants::FORM_CREATE; ?>" value="<?php echo Constants::FORM_CREATE; ?>"><?php echo __('Publicar'); ?></button>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading"><?php echo __('Categorías'); ?></div>
                        <div class="panel-body">
                            <select name="<?php echo PostsCategoriesManager::CATEGORY_ID; ?>[]" multiple class="form-control">
                                <?php array_walk($categories, function(Category $category) use ($selectedCategoriesId) {
                                    $categoryID = $category->getID();
                                    $selected   = '';
    
                                    if (Arrays::valueExists($selectedCategoriesId, $categoryID)) {
                                        $selected = 'selected';
                                    }
    
                                    echo "<option value='$categoryID' $selected>" . $category->getCategoryName() . '</option>';
                                }); ?>
                            </select>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading"><?php echo __('Etiquetas'); ?></div>
                        <div class="panel-body">
                            <select name="<?php echo PostsTermsManager::TERM_ID; ?>[]" multiple class="form-control">
                                <?php array_walk($terms, function(Term $term) use ($selectedTermsId) {
                                    $termID   = $term->getID();
                                    $selected = '';
    
                                    if (Arrays::valueExists($selectedTermsId, $termID)) {
                                        $selected = 'selected';
                                    }
    
                                    echo "<option value='$termID' $selected>" . $term->getTermName() . '</option>';
                                }); ?>
                            </select>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="<?php echo PostsManager::COLUMN_ID; ?>" value="<?php echo $post->getId(); ?>"/>
                <?php \SoftnCMS\util\Token::formField(); ?>
            </form>
        </div>
    </div>
</div>
