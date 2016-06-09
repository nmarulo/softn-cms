<?php

/**
 * Controlador de la pagina de publicaciones.
 */
require 'sn-admin.php';
SN_Users::checkRol('editor', true);

/**
 * Fecha de publicación o actualizacion del post.
 */
$date = getDate_now();
/*
 * Recoge los datos del post. Para mostrar los datos en
 * sus campos correspontiendes del modelo vista.
 */
$post = [
    'ID' => 0,
    'post_title' => '',
    'post_contents' => '',
    'relationships_category_ID' => [],
    'relationships_term_ID' => [],
    'post_status' => 1,
    'post_date' => '0000-00-00 00:00:00',
    'post_update' => '0000-00-00 00:00:00',
    'post_contents' => '',
    'comment_status' => 1,
    'comment_count' => 0,
    'users_ID' => 1,
    'post_type' => 'post'
];
//Cambia el encabezado y los botones de publicar y actualizar
$action_edit = false;

if ((filter_input(INPUT_GET, 'action') == 'edit' && filter_input(INPUT_GET, 'id')) || filter_input(INPUT_POST, 'update')) {
    $action_edit = true;
    $auxPost = SN_Posts::get_instance(filter_input(INPUT_GET, 'id'));

    if ($auxPost) {
        //Comprueba si el usuario es el autor de la publicación
        if ($auxPost->getUsers_ID() == SN_Users::getSession()->getID() || SN_Users::checkRol('author', true)) {
            
            //array con los ID de las categorias que contenga el post
            $categories = array_column(SN_Posts_Categories::getCategoriesID($auxPost->getID()), 'relationships_category_ID');

            //array con los ID de las etiquetas que contenga el post
            $terms = array_column(SN_Posts_Terms::getTermsID($auxPost->getID()), 'relationships_term_ID');

            if (filter_input(INPUT_POST, 'update')) {
                $post_title = filter_input(INPUT_POST, 'post_title');
                $post_comment_status = filter_input(INPUT_POST, 'post_comment_status');
                $post_status = filter_input(INPUT_POST, 'post_status');
                $post_contents = filter_input(INPUT_POST, 'post_contents');
                $postCategories = filter_input(INPUT_POST, 'relationships_category_ID', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
                $postTerms = filter_input(INPUT_POST, 'relationships_term_ID', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

                $arg = [
                    'ID' => $auxPost->getID(),
                    'post_title' => $post_title,
                    'post_status' => $post_status,
                    'post_update' => $date,
                    'post_contents' => $post_contents,
                    'comment_status' => $post_comment_status,
                    'comment_count' => $auxPost->getComment_count(),
                    'users_ID' => $auxPost->getUsers_ID(),
                    'post_type' => $auxPost->getPost_type(),
//                'post_date' => '0000-00-00 00:00:00', //No se asigna ya que en el update no se pasa esta columna
                ];

                $auxPost = new SN_Posts($arg);

                if ($auxPost->update()) {
                    $error = 0;
                    /*
                     * Si no se han seleccionado categorias
                     * la borrara todas, solo si tenia alguna previamente
                     */
                    if (count($postCategories)) {
                        //Comprueba e inserta solo la nuevas categorias
                        foreach ($postCategories as $categoria) {
                            if (!in_array($categoria, $categories)) {
                                if (!SN_Posts_Categories::insert($auxPost->getID(), $categoria)) {
                                    Messages::add("No fue posible vincular la categoria [ID=$categoria].", Messages::TYPE_E);
                                    ++$error;
                                }
                            }
                        }

                        //Comprueba y borra las categorias en caso de que hayan cambiado
                        foreach ($categories as $categoria) {
                            if (!in_array($categoria, $postCategories)) {
                                if (!SN_Posts_Categories::delete($auxPost->getID(), $categoria)) {
                                    ++$error;
                                }
                            }
                        }
                    } else if (count($categories)) {
                        if (!SN_Posts_Categories::delete($auxPost->getID())) {
                            ++$error;
                        }
                    }

                    /*
                     * Si no se han seleccionado etiquetas
                     * la borrara todas, solo si tenia alguna
                     */
                    if (count($postTerms)) {
                        //Comprueba e inserta solo la nuevas categorias
                        foreach ($postTerms as $term) {
                            if (!in_array($term, $terms)) {
                                if (!SN_Posts_Terms::insert($auxPost->getID(), $term)) {
                                    ++$error;
                                }
                            }
                        }

                        //Comprueba y borra las categorias en caso de que hayan cambiado
                        foreach ($terms as $term) {
                            if (!in_array($term, $postTerms)) {
                                if (!SN_Posts_Terms::delete($auxPost->getID(), $term)) {
                                    ++$error;
                                }
                            }
                        }
                    } else if (count($terms)) {
                        if (!SN_Posts_Terms::delete($auxPost->getID())) {
                            ++$error;
                        }
                    }

                    $msg = $error ? 'Error al actualizar las categorias o etiquetas.' : 'Entrada actualizada.';
                    Messages::add($msg, $error ? Messages::TYPE_E : Messages::TYPE_S);
                } else {
                    Messages::add('Error al actualizar entrada.', Messages::TYPE_E);
                }
            }

            $post = [
                'ID' => $auxPost->getID(),
                'post_contents' => $auxPost->getPost_contents(),
                'post_title' => $auxPost->getPost_title(),
                'relationships_category_ID' => $categories,
                'relationships_term_ID' => $terms,
                'post_status' => $auxPost->getPost_status(),
                'post_date' => $auxPost->getPost_date(),
                'post_update' => $auxPost->getPost_update(),
                'comment_status' => $auxPost->getComment_status(),
                'comment_count' => $auxPost->getComment_count(),
                'users_ID' => $auxPost->getUsers_ID(),
                'post_type' => $auxPost->getPost_type(),
            ];
        }
    } else {
        Messages::add('La entrada no existe.', Messages::TYPE_E);
    }
} elseif (filter_input(INPUT_POST, 'publish')) {
    $post_title = filter_input(INPUT_POST, 'post_title');
    $post_contents = filter_input(INPUT_POST, 'post_contents');
    $post_comment_status = filter_input(INPUT_POST, 'post_comment_status');
    $post_status = filter_input(INPUT_POST, 'post_status');
    $postCategories = filter_input(INPUT_POST, 'relationships_category_ID', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $postTerms = filter_input(INPUT_POST, 'relationships_term_ID', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

    $post = [
        'post_title' => $post_title,
        'post_date' => $date,
        'post_update' => $date,
        'post_status' => $post_status,
        'post_contents' => $post_contents,
        'users_ID' => SN_Users::getSession()->getID(),
        'post_type' => 'post',
        'comment_status' => $post_comment_status,
        'relationships_category_ID' => $postCategories,
        'relationships_term_ID' => $postTerms,
    ];

    $auxPost = new SN_Posts($post);

    if ($auxPost->insert()) {
        $error = 0;
        /*
         * Si se han seleccionado categorias, se agregaran a la tabla
         * que relaciona el post con una categoria.
         */
        if ($postCategories) {
            foreach ($postCategories as $categoryID) {
                if (!SN_Posts_Categories::insert($auxPost->getID(), $categoryID)) {
                    Messages::add("No fue posible vincular la categoria [ID=$categoryID].", Messages::TYPE_E);
                    ++$error;
                }
            }
        }

        /*
         * Si se han seleccionado etiquetas, se agregaran a la tabla
         * que relaciona el post con una etiqueta.
         */
        if ($postTerms) {
            foreach ($postTerms as $termID) {
                if (!SN_Posts_Terms::insert($auxPost->getID(), $termID)) {
                    Messages::add("No fue posible vincular la etiqueta [ID=$termID].", Messages::TYPE_E);
                    ++$error;
                }
            }
        }
        if ($error) {
            Messages::add('Error al vincular las etiquetas o categorias.', Messages::TYPE_E);
        } else {
            Messages::add('Entrada publicada.', Messages::TYPE_S);
            redirect('posts', ADM);
        }
    } else {
        Messages::add('Error al publicar entrada.', Messages::TYPE_E);
    }
}

require ABSPATH . ADM_CONT . 'post-new.php';
