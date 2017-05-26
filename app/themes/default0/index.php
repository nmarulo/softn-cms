<br/>
<div class="container"><!-- .container -->
    <div id="content" class="row clearfix"><!-- .row.clearfix -->
        <div id="main" class="col-md-8"><!-- #main -->
            <?php foreach ($data['posts'] as $post) { ?>
                <!-- post -->
                <article id="post-<?php echo $post->getID(); ?>" class="">
                    <header>
                        <div class="page-header">
                            <h1 class="h2">
                                <a href="<?php echo $data['siteUrl'] . 'post/' . $post->getID(); ?>"><?php echo $post->getPostTitle(); ?></a>
                            </h1>
                        </div>
                        <p class="meta">
                            <time class="label label-primary" datetime="2015/01/22"><span class="glyphicon glyphicon-time"></span> <?php echo $post->getPostDate(); ?></time>
                            <span class="glyphicon glyphicon-user"></span> Publicado por <?php echo $post->getUser()->getUserName(); ?>/
                            <span class=" glyphicon glyphicon-folder-open"></span> Archivado en categoria.
                        </p>
                        <hr />
                    </header>
                    <section class="post_content clearfix">
                        <?php echo $post->getPostContents(); ?>
                    </section>
                    <hr/>
                    <footer>
                        <p>Etiquetas: <a class="label label-default" href="#">titulo</a></p>
                    </footer>
                </article>
                <!-- comentarios -->
                <?php if ($post->getCommentCount()) { ?>
                    <div>
                        <h2>Comentarios <small><span class="label label-warning"><?php echo $post->getCommentCount(); ?></span></small></h2>
                        <div id="comment-#" class="media">
                            <div class="media-left">
                                <a href="#">
                                    <img class="media-object" src="<?php echo $data['siteUrl']; ?>app/themes/default/img/avatar.svg">
                                </a>
                            </div>
                            <div class="media-body">
                                <div class="row clearfix">
                                    <div class="col-md-6">
                                        <a href="#" target="_blank">autor</a>
                                    </div>
                                    <div class="col-md-6">fecha</div>
                                </div>
                                <p>contenido</p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($post->getCommentStatus()) { ?>
                    <div class="">
                        <h2>Publicar comentario</h2>
                        <form class="form-horizontal" method="post">
                            <?php if (empty($this->data['data']['userSession'])) { ?>
                                <div class="form-group">
                                    <label class="control-label">Nombre</label>
                                    <input type="text" class="form-control" name="commentAutor"/>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Correo electronico</label>
                                    <input type="email" class="form-control" name="commentAuthorEmail"/>
                                </div>
                            <?php } else { ?>
                                <div class="form-group"><p>Conectado como <a href="<?php echo $data['siteUrl'] . 'author/' . $data['userSession']->getID(); ?>" target="_blank"><?php echo $data['userSession']->getUserName(); ?></a></p></div>
                            <?php } ?>
                            <div class="form-group">
                                <label class="control-label">Comentario</label>
                                <textarea class="form-control" name="commentContents" rows="5"></textarea>
                            </div>
                            <button class="btn btn-primary" name="publish" value="publish" type="submit">Publicar</button>
                        </form>
                    </div>
                <?php } else { ?>
                    <div class="alert alert-info">Los comentarios estan cerrados.</div>
                <?php }//end if ?>
            <?php }//end foreach ?>
        </div><!-- #main -->

        <div id="sidebar" class="col-md-4"><!-- #sidebar -->
            <div class="panel panel-primary">
                <div class="panel-body">
                    <a href="<?php echo $data['siteUrl']; ?>admin">Administración</a>
                </div>
            </div>
            <div id="" class="widget panel panel-default">
                <div class="titleWidget panel-heading">
                    <h4 class="">titulo</h4>
                </div>
                <div class="contentWidget panel-body">
                    contenido
                </div>
            </div>
        </div><!-- #sidebar -->
    </div><!-- .row.clearfix -->
</div><!-- .container -->
