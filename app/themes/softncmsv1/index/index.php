<?php
use SoftnCMS\controllers\ViewController;

$posts   = ViewController::getViewData('posts');
$siteUrl = "";
$urlPost = $siteUrl . 'post/';
?>
    <main>
    <?php foreach ($posts as $postTemplate) {
        $post       = $postTemplate->getPost();
        $user       = $postTemplate->getUserTemplate()
                                   ->getUser();
        $terms      = $postTemplate->getTermsTemplate();
        $categories = $postTemplate->getCategoriesTemplate();
        $postId     = $post->getId();
        ?>
        <article id="post-<?php echo $postId; ?>" class="bg-grey">
        <header class="clearfix">
            <div class="post-title clearfix">
                <h2 class="h3">
                    <a href="<?php echo $urlPost . $postId; ?>"><?php echo $post->getPostTitle(); ?></a>
                </h2>
            </div>
            <p class="meta">
                <time class="label label-primary" datetime="2015/01/22"><span class="glyphicon glyphicon-time"></span> <?php echo $post->getPostDate(); ?></time>
                <span class="glyphicon glyphicon-user"></span> Publicado por
                <a href="#"><?php echo $user->getUserName(); ?></a>/
                <span class=" glyphicon glyphicon-folder-open"></span> Archivado en
                <?php foreach ($categories as $category) { ?>
                    <a class="label label-default" href="#"><?php echo $category->getCategory()
                                                                                ->getCategoryName(); ?></a>
                <?php } ?>
            </p>
        </header>
        <section><?php echo $post->getPostContents(); ?></section>
        <footer>
            <p>
                Etiquetas:
                <?php foreach ($terms as $term) { ?>
                    <a class="label label-default" href="#"><?php echo $term->getTerm()
                                                                            ->getTermName(); ?></a>
                <?php } ?>
            </p>
        </footer>
    </article>
    <?php } ?>
</main>
<?php
ViewController::singleViewDirectoryViews('pagination');
