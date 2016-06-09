<?php
//function get_pages(){}
//function get_categoies(){}
//function get_posts(){}
//function get_terms(){}

/**
 * Obtiene el encabezado de la pagina. Carpeta "sn-content"
 */
function get_header() {
    include ABSPATH . CONT . 'header.php';
}

/**
 * Obtiene la barra lateral de la pagina. Carpeta "sn-content"
 */
function get_sidebar() {
    include ABSPATH . CONT . 'sidebar.php';
}

/**
 * Obtiene el pie de pagina. Carpeta "sn-content"
 */
function get_footer() {
    include ABSPATH . CONT . 'footer.php';
}

/**
 * Muestra u obtiene la direccion principal de la pagina.
 * @global type $dataTable
 * @param boolean $echo Si es true, imprime la url del sitio.
 * @return mixed
 */
function siteUrl($echo = false) {
    global $dataTable;

    if ($echo) {
        echo $dataTable['option']['siteUrl'];
    } else {
        return $dataTable['option']['siteUrl'];
    }
}

/**
 * Muestra u obtiene el titulo de la pagina.
 * @global type $dataTable
 * @param type $echo
 * @return mixed
 */
function siteTitle($echo = false) {
    global $dataTable;
    if ($echo) {
        echo $dataTable['option']['siteTitle'];
    } else {
        return $dataTable['option']['siteTitle'];
    }
}

function get_content() {
    global $post;

    echo $post->getPost_contents();
}

function get_title() {
    global $post;

    echo $post->getPost_title();
}

function get_author() {
    global $post;

    $author = SN_Users::get_instance($post->getUsers_ID());
    echo '<a href="' . siteUrl() . '?author=' . $author->getID() . '">' . $author->getUser_name() . '</a>';
}

function get_category() {
    global $post;

    $out = '';
    $categories = SN_Posts_Categories::getCategories($post->getID());

    foreach ($categories as $category) {
        $category = SN_Categories::get_instance($category['ID']);

        $out .= '<a href="' . siteUrl() . '?category=' . $category->getID() . '">' . $category->getCategory_name() . '</a>, ';
    }

    echo $out;
}

function get_terms() {
    global $post;

    $out = '';
    $terms = SN_Posts_Terms::getTerms($post->getID());

    foreach ($terms as $term) {
        $term = SN_Terms::get_instance($term['ID']);

        $out .= '<a class="label label-default" href="' . siteUrl() . '?term=' . $term->getID() . '">' . $term->getTerm_name() . '</a> ';
    }

    echo $out;
}

function get_date() {
    global $post;

    echo $post->getPost_date();
}

function get_post_ID() {
    global $post;
    echo $post->getID();
}

function have_posts() {
    global $posts, $post, $dataTable;

    $post = $posts->fetchObject();
    if (empty($post)) {
        $post = false;
    } else {
        $post = SN_Posts::get_instance($post->ID);
        $dataTable['post']['url'] = siteUrl() . '?post=' . $post->getID();
    }

    return $post;
}

function get_post_url() {
    global $dataTable;
    return $dataTable['post']['url'];
}

function paged($paged) {
    return '0,1';
}

/**
 * Obtiene la lista de paginas
 */
function get_paged() {
    
}

function have_sibebars() {
    global $sidebars, $sidebar;

    $sidebar = $sidebars->fetchObject();
    if (empty($sidebar)) {
        $sidebar = false;
    } else {
        $sidebar = SN_Sidebars::get_instance($sidebar->ID);
    }

    return $sidebar;
}

function getSidebar_title() {
    global $sidebar;
    echo $sidebar->getSidebar_title();
}

function getSidebar_contents() {
    global $sidebar;
    echo $sidebar->getSidebar_contents();
}

function getNavigationMenu() {
    ?>
    <nav class="navbar navbar-default"><!-- nav.navbar -->
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php siteUrl(true); ?>"><?php siteTitle(true); ?></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <?php
                    $menuParent = SN_Menus::dataList('fetchObject');
                    if ($menuParent) {
                        $menuItems = SN_Menus::getChildrens($menuParent->ID);
                        foreach ($menuItems as $item) {
                            $subItems = SN_Menus::getChildrens($item['ID']);
                            if ($subItems) {
                                ?>
                                <li class="dropdown">
                                    <a href="<?php echo $item['menu_url']; ?>" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                        <?php echo $item['menu_title']; ?> <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu" role="menu">
                                        <?php
                                        foreach ($subItems as $sub) {
                                            echo "<li><a href='$sub[menu_url]'>$sub[menu_title]</a></li>";
                                        }
                                        ?>
                                    </ul>
                                </li>
                                <?php
                            } else {
                                echo "<li><a href='$item[menu_url]'>$item[menu_title]</a></li>";
                            }
                        }
                    }
                    ?>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav><!-- nav.navbar -->
    <?php
}

function get_comments() {
    global $post, $dataTable;

    if ($post->getComment_count()) {
        $comments = SN_Posts::dataListComments($post->getID());

        if ($comments) {
            ?>
            <div>
                <h2>Comentarios</h2>
                <?php
                $messages = Messages::getMessages();
                if ($messages) {
                    $str = '';
                    foreach ($messages as $message) {
                        $str .= "<div class='alert alert-" . $message['type'] . " alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>" . $message['message'] . '</div>';
                    }
                    echo $str;
                }
                foreach ($comments as $comment) {
                    $userUrl = $comment['comment_user_ID'] ? $comment['comment_user_ID'] : 0;
                    ?>
                    <div id="comment-<?php echo $comment['ID']; ?>" class="media">
                        <div class="media-left">
                            <a href="#">
                                <img class="media-object" src="sn-content/themes/<?php echo $dataTable['option']['theme'] ?>/img/avatar.svg">
                            </a>
                        </div>
                        <div class="media-body">
                            <div class="row clearfix">
                                <div class="col-md-6">
                                    <?php if ($comment['comment_user_ID']) { ?>
                                        <a href="?author=<?php echo $comment['comment_user_ID']; ?>" target="_blank"><?php echo $comment['comment_autor']; ?></a>
                                        <?php
                                    } else {
                                        echo $comment['comment_autor'];
                                    }
                                    ?>
                                </div>
                                <div class="col-md-6"><?php echo $comment['comment_date']; ?></div>
                            </div>
                            <p><?php echo $comment['comment_contents']; ?></p>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php
        }
    }

    if ($post->getComment_status()) {
        ?>
        <div class="">
            <h2>Publicar comentario</h2>
            <form class="form-horizontal" method="post">
                <?php
                $username = SN_Users::getSession();
                if (empty($username)) {
                    ?>
                    <div class="form-group">
                        <label class="control-label">Nombre</label>
                        <input type="text" class="form-control" name="comment_autor"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Correo electronico</label>
                        <input type="email" class="form-control" name="comment_author_email"/>
                    </div>
                    <?php
                } else {
                    echo '<div class="form-group"><p>Conectado como <a href="?author=' . $username->getID() . '" target="_blank">' . $username->getUser_name() . '</a></p></div>';
                }
                ?>
                <div class="form-group">
                    <label class="control-label">Comentario</label>
                    <textarea class="form-control" name="comment_contents" rows="5"></textarea>
                </div>
                <button class="btn bg-primary" name="publish" value="publish" type="submit">Publicar</button>
            </form>
        </div>
        <?php
    } else {
        echo '<div class="alert alert-info">Los comentarios estan cerrados.</div>';
    }
}
