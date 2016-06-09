<?php

/**
 * Controlador de la pagina de paginas nuevas.
 */
require 'sn-admin.php';
SN_Users::checkRol('author', true);

/**
 * Fecha de publicación o actualizacion del post.
 */
$date = getDate_now();
/*
 * Recoge los datos del post. Para mostrar los datos en
 * sus campos correspontiendes del modelo vista.
 */
$page = [
    'ID' => 0,
    'post_title' => '',
    'post_status' => 1,
    'post_date' => '0000-00-00 00:00:00',
    'post_update' => '0000-00-00 00:00:00',
    'post_contents' => '',
    'comment_status' => 1,
    'comment_count' => 0,
    'users_ID' => 1,
    'post_type' => 'page'
];
//Cambia el encabezado y los botones de publicar y actualizar
$action_edit = false;

if ((filter_input(INPUT_GET, 'action') == 'edit' && filter_input(INPUT_GET, 'id')) || filter_input(INPUT_POST, 'update')) {
    $action_edit = true;
    $auxPage = SN_Posts::get_instance(filter_input(INPUT_GET, 'id'));

    if ($auxPage) {

        if (filter_input(INPUT_POST, 'update')) {
            $post_title = filter_input(INPUT_POST, 'post_title');
            $post_contents = filter_input(INPUT_POST, 'post_contents');
            $post_comment_status = filter_input(INPUT_POST, 'post_comment_status');
            $post_status = filter_input(INPUT_POST, 'post_status');

            $arg = [
                'ID' => $auxPage->getID(),
                'post_title' => $post_title,
                'post_status' => $post_status,
                'post_update' => $date,
                'post_contents' => $post_contents,
                'comment_status' => $post_comment_status,
                'comment_count' => $auxPage->getComment_count(),
                'users_ID' => $auxPage->getUsers_ID(),
                'post_type' => $auxPage->getPost_type(),
            ];

            $auxPage = new SN_Posts($arg);

            if ($auxPage->update()) {
                Messages::add('Pagina actualizada.', Messages::TYPE_S);
            } else {
                Messages::add('Error al actualizar la pagina.', Messages::TYPE_E);
            }
        }

        $page = [
            'ID' => $auxPage->getID(),
            'post_title' => $auxPage->getPost_title(),
            'post_status' => $auxPage->getPost_status(),
            'post_update' => $auxPage->getPost_update(),
            'post_date' => $auxPage->getPost_date(),
            'post_contents' => $auxPage->getPost_contents(),
            'comment_status' => $auxPage->getComment_status(),
            'comment_count' => $auxPage->getComment_count(),
            'users_ID' => $auxPage->getUsers_ID(),
            'post_type' => $auxPage->getPost_type(),
        ];
    } else {
        Messages::add('La entrada no existe.', Messages::TYPE_E);
    }
} elseif (filter_input(INPUT_POST, 'publish')) {
    $post_title = filter_input(INPUT_POST, 'post_title');
    $post_contents = filter_input(INPUT_POST, 'post_contents');
    $post_status = filter_input(INPUT_POST, 'post_status');
    $post_comment_status = filter_input(INPUT_POST, 'post_comment_status');

    $page = [
        'post_title' => $post_title,
        'post_date' => $date,
        'post_update' => $date,
        'post_status' => $post_status,
        'post_contents' => $post_contents,
        'comment_status' => $post_comment_status,
        'users_ID' => SN_Users::getSession()->getID(),
        'post_type' => 'page',
    ];

    $auxPage = new SN_Posts($page);

    if ($auxPage->insert()) {
        Messages::add('Pagina publicada.', Messages::TYPE_S);
        redirect('pages', ADM);
    } else {
        Messages::add('Error al publicar la pagina.', Messages::TYPE_E);
    }
}

require ABSPATH . ADM_CONT . 'page-new.php';
