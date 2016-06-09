<?php
/**
 * Controlador de la plantilla web.
 */

require INC . 'functions.php';
require INC . 'template.php';

//Indica si se usa la pagina del index o no.
$index = true;

/**
 * Guarda la información del post a mostrar.
 */
$post;

$sidebar;

/**
 * Obtiene todos los post de la base de datos.
 */
$posts = SN_Posts::dataList(false, 'post');

$sidebars = SN_Sidebars::dataList(false);

if (filter_input(INPUT_GET, 'post')) {
    $id = filter_input(INPUT_GET, 'post');

    if (filter_input(INPUT_POST, 'publish')) {
        $comment_autor = filter_input(INPUT_POST, 'comment_autor');
        $comment_author_email = filter_input(INPUT_POST, 'comment_author_email');
        $comment_contents = filter_input(INPUT_POST, 'comment_contents');
        $comment_user_ID = SN_Users::getSession()->getID();
        
        if($comment_user_ID){
            $user = SN_Users::get_instance($comment_user_ID);
            $comment_autor = $user->getUser_name();
            $comment_author_email = $user->getUser_email();
        }
        
        $arg = [
            'comment_status' => $comment_user_ID ? 1 : 0,
            'comment_autor' => $comment_autor,
            'comment_author_email' => $comment_author_email,
            'comment_date' => date('Y-m-d H:i:s'),
            'comment_contents' => $comment_contents,
            'comment_user_ID' => $comment_user_ID,
            'post_ID' => $id,
        ];
        
        $comment = new SN_Comments($arg);
        if($comment->insert()){
            Messages::add('Comentario enviado correctamente.', Messages::TYPE_S);
        }else{
            Messages::add('Error al enviar el comentario.', Messages::TYPE_E);
        }
    }

    /**
     * Guarda el post segun el ID pasado por URL
     */
    $posts = SN_Posts::get_instance($id, 'PDOStatement');

//    if(file_exists(CONT . 'single.php')){
//        $index = false;
//        require CONT . 'single.php';
//    }
} elseif (filter_input(INPUT_GET, 'category')) {
    $id = filter_input(INPUT_GET, 'category');
    $posts = SN_Posts_Categories::getPosts($id);
} elseif (filter_input(INPUT_GET, 'term')) {
    $id = filter_input(INPUT_GET, 'term');
    $posts = SN_Posts_Terms::getPosts($id);
} elseif (filter_input(INPUT_GET, 'author')) {
    $id = filter_input(INPUT_GET, 'author');
    $posts = SN_Users::getPosts($id);
} elseif (filter_input(INPUT_GET, 'page')) {
    $id = filter_input(INPUT_GET, 'page');
    $posts = SN_Posts::get_instance($id, 'PDOStatement');
}

if ($index) {
    require THEMES . $dataTable['option']['theme'] . '/index.php';
}
