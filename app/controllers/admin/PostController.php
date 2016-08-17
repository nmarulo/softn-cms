<?php

/**
 * Modulo del controlador de la pagina de entradas.
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\BaseController;
use SoftnCMS\controllers\Messages;
use SoftnCMS\models\admin\Post;
use SoftnCMS\models\admin\Posts;
use SoftnCMS\models\admin\PostUpdate;
use SoftnCMS\models\admin\PostInsert;
use SoftnCMS\models\admin\PostDelete;

/**
 * Clase del controlador de la pagina de entradas.
 *
 * @author Nicolás Marulanda P.
 */
class PostController extends BaseController {

    /**
     * Metodo llamado por la función INDEX.
     * @return array
     */
    protected function dataIndex() {
        $posts = Posts::selectAll();
        $output = $posts->getPosts();

        foreach ($output as $post) {
            $title = $post->getPostTitle();

            if (isset($title{30})) {
                $title = substr($title, 0, 30) . ' [...]';
            }
            $post->setPostTitle($title);
        }

        return ['posts' => $output];
    }

    /**
     * Metodo llamado por la función INSERT.
     * @return array
     */
    protected function dataInsert() {
        global $urlSite;

        if (filter_input(\INPUT_POST, 'publish')) {

            $dataInput = $this->getDataInput();
            $insert = new PostInsert($dataInput['postTitle'], $dataInput['postContents'], $dataInput['commentStatus'], $dataInput['postStatus'], $_SESSION['usernameID']);

            if ($insert->insert()) {
                Messages::addSuccess('Entrada publicada correctamente.');
                //Si todo es correcto se muestra el POST en la pagina de edición.
                header("Location: $urlSite" . 'admin/post/update/' . $insert->getLastInsertId());
                exit();
            }
            Messages::addError('Error al publicar la entrada');
        }

        return [
            //Datos por defecto a mostrar en el formulario.
            'post' => Post::defaultInstance(),
            /*
             * Booleano que indica si muestra el encabezado
             * "Publicar nueva entrada" si es FALSE 
             * o "Actualizar entrada" si es TRUE
             */
            'actionUpdate' => \FALSE
        ];
    }

    /**
     * Metodo llamado por la función UPDATE.
     * @param int $id
     * @return array
     */
    protected function dataUpdate($id) {
        global $urlSite;

        $post = Post::selectByID($id);

        //En caso de que no exista.
        if (empty($post)) {
            Messages::addError('Error. La entrada no existe.');
            header("Location: $urlSite" . 'admin/post');
            exit();
        }

        if (filter_input(\INPUT_POST, 'update')) {
            $dataInput = $this->getDataInput();
            $update = new PostUpdate($post, $dataInput['postTitle'], $dataInput['postContents'], $dataInput['commentStatus'], $dataInput['postStatus']);

            //Si ocurre un error la función "$update->update()" retorna FALSE.
            if ($update->update()) {
                Messages::addSuccess('Entrada actualizada correctamente.');
                $post = $update->getPost();
            } else {
                Messages::addError('Error al actualizar la entrada.');
            }
        }

        return [
            //Instancia POST
            'post' => $post,
            /*
             * Booleano que indica si muestra el encabezado
             * "Publicar nueva entrada" si es FALSE 
             * o "Actualizar entrada" si es TRUE
             */
            'actionUpdate' => \TRUE
        ];
    }

    /**
     * Metodo llamado por la función DELETE.
     * @param int $id
     * @return array
     */
    protected function dataDelete($id) {
        /*
         * Ya que este metodo no tiene modulo vista propio
         * se carga el modulo vista INDEX, asi que se retornan los datos
         * para esta vista.
         */

        $delete = new PostDelete($id);

        if ($delete->delete()) {
            Messages::addSuccess('Entrada borrada correctamente.');
        } else {
            Messages::addError('Error al borrar la entrada.');
        }

        return $this->dataIndex();
    }

    /**
     * Metodo que obtiene los datos de los campos INPUT del formulario.
     * @return array
     */
    protected function getDataInput() {
        return [
            'postTitle' => \filter_input(\INPUT_POST, 'postTitle'),
            'postContents' => \filter_input(\INPUT_POST, 'postContents'),
            'commentStatus' => \filter_input(\INPUT_POST, 'commentStatus'),
            'postStatus' => \filter_input(\INPUT_POST, 'postStatus'),
//            'relationshipsCategoryID' => \filter_input(\INPUT_POST, 'relationshipsCategoryID', \FILTER_DEFAULT, \FILTER_REQUIRE_ARRAY),
//            'relationshipsTermID' => \filter_input(\INPUT_POST, 'relationshipsTermID', \FILTER_DEFAULT, \FILTER_REQUIRE_ARRAYRAY),
        ];
    }

}
