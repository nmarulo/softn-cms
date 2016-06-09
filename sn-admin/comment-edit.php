<?php

/**
 * Controlador de la pagina de edición de comentarios.
 */
require 'sn-admin.php';
SN_Users::checkRol('admin', true);

/*
 * Recoge los datos del comentario. Para mostrar los datos en
 * sus campos correspontiendes del modelo vista.
 */
$comment = [
    'ID' => 0,
    'comment_status' => 0,
    'comment_autor' => '',
    'comment_author_email' => '',
    'comment_date' => '0000-00-00 00:00:00',
    'comment_contents' => '',
    'comment_user_ID' => 0,
    'post_ID' => 0,
    'comment_post_title' => '',
];


if ((filter_input(INPUT_GET, 'action') == 'edit' && filter_input(INPUT_GET, 'id')) || filter_input(INPUT_POST, 'update')) {

    $auxComment = SN_Comments::get_instance(filter_input(INPUT_GET, 'id'));

    if ($auxComment) {

        if (filter_input(INPUT_POST, 'update')) {
            $comment_status = filter_input(INPUT_POST, 'comment_status');
            $comment_contents = filter_input(INPUT_POST, 'comment_contents');

            $arg = [
                'ID' => $auxComment->getID(),
                'comment_status' => $comment_status,
                'comment_autor' => $auxComment->getComment_autor(),
                'comment_author_email' => $auxComment->getComment_author_email(),
                'comment_date' => $auxComment->getComment_date(),
                'comment_contents' => $comment_contents,
                'comment_user_ID' => $auxComment->getComment_user_ID(),
                'post_ID' => $auxComment->getPost_ID(),
            ];

            $auxComment = new SN_Comments($arg);

            if ($auxComment->update()) {
                Messages::add('Comentario actualizado.', Messages::TYPE_S);
            } else {
                Messages::add('Error al actualizar el comentario.', Messages::TYPE_E);
            }
        }

        $comment = [
            'ID' => $auxComment->getID(),
            'comment_status' => $auxComment->getComment_status(),
            'comment_autor' => $auxComment->getComment_autor(),
            'comment_author_email' => $auxComment->getComment_author_email(),
            'comment_date' => $auxComment->getComment_date(),
            'comment_contents' => $auxComment->getComment_contents(),
            'comment_user_ID' => $auxComment->getComment_user_ID(),
            'post_ID' => $auxComment->getPost_ID(),
            'comment_post_title' => SN_Posts::get_instance($auxComment->getPost_ID())->getPost_title(),
            'comment_post_url' => siteUrl() . '?post=' . $auxComment->getPost_ID(),
        ];
    } else {
        Messages::add('El comentario no existe.', Messages::TYPE_E);
    }
}

require ABSPATH . ADM_CONT . 'comment-edit.php';
