<?php

use SoftnCMS\controllers\ViewController;

$pageTemplate = ViewController::getViewData('page');
$siteUrl      = $pageTemplate->getSiteUrl();
$urlPage      = $siteUrl . 'page/';
$page         = $pageTemplate->getPage();
$pageId       = $page->getId();
?>
<main>
    <article id="page-<?php echo $pageId; ?>" class="bg-grey">
        <header class="clearfix">
            <div class="post-title clearfix">
                <h2 class="h3">
                    <a href="<?php echo $urlPage . $pageId; ?>">
                        <?php echo $page->getPageTitle(); ?>
                    </a>
                </h2>
            </div>
            <p class="meta">
                <time class="label label-primary" datetime="2015/01/22"><span class="glyphicon glyphicon-time"></span> <?php echo $page->getPageDate(); ?></time>
                
            </p>
        </header>
        <section><?php echo $page->getPageContents(); ?></section>
    </article>
</main>
