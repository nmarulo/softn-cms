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
use SoftnCMS\models\admin\Categories;
use SoftnCMS\models\admin\Terms;
use SoftnCMS\models\admin\PostCategoryInsert;
use SoftnCMS\models\admin\PostsCategories;
use SoftnCMS\models\admin\PostCategoryDelete;
use SoftnCMS\models\admin\PostsTerms;
use SoftnCMS\models\admin\PostTermInsert;
use SoftnCMS\models\admin\PostTermDelete;

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
        $output = [];
        
        if($posts !== \FALSE){
            $output = $posts->getAll();
        }

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

        $outTerms = [];
        $outCategories = [];
        $categories = Categories::selectAll();
        $terms = Terms::selectAll();
        
        if($terms !== \FALSE){
            $outTerms = $terms->getAll();
        }
        
        if($categories !== \FALSE){
            $outCategories = $categories->getAll();
        }

        if (filter_input(\INPUT_POST, 'publish')) {
            $dataInput = $this->getDataInput();
            $insert = new PostInsert($dataInput['postTitle'], $dataInput['postContents'], $dataInput['commentStatus'], $dataInput['postStatus'], $_SESSION['usernameID']);

            if ($insert->insert()) {
                Messages::addSuccess('Entrada publicada correctamente.');
                $postID = $insert->getLastInsertId();

                $this->insertRelationshipsCategories($dataInput['relationshipsCategoriesID'], $postID);
                $this->insertRelationshipsTerms($dataInput['relationshipsTermsID'], $postID);

                //Si todo es correcto se muestra el POST en la pagina de edición.
                header("Location: $urlSite" . 'admin/post/update/' . $postID);
                exit();
            }
            Messages::addError('Error al publicar la entrada');
        }

        return [
            'terms' => $outTerms,
            'categories' => $outCategories,
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

        $postID = $post->getID();
        $outTerms = [];
        $outCategories = [];
        $categories = Categories::selectAll();
        $terms = Terms::selectAll();
        
        if($terms !== \FALSE){
            $outTerms = $terms->getAll();
        }
        
        if($categories !== \FALSE){
            $outCategories = $categories->getAll();
        }

        if (filter_input(\INPUT_POST, 'update')) {
            $dataInput = $this->getDataInput();
            $update = new PostUpdate($post, $dataInput['postTitle'], $dataInput['postContents'], $dataInput['commentStatus'], $dataInput['postStatus']);
            
            //Si ocurre un error la función "$update->update()" retorna FALSE.
            if ($update->update()) {
                Messages::addSuccess('Entrada actualizada correctamente.');
                $post = $update->getDataUpdate();

                $this->updateRelationshipsCategories($dataInput['relationshipsCategoriesID'], $postID);
                $this->updateRelationshipsTerms($dataInput['relationshipsTermsID'], $postID);
            } else {
                Messages::addError('Error al actualizar la entrada.');
            }
        }

        return [
            'relationshipsCategoriesID' => PostsCategories::selectByPostID($postID),
            'relationshipsTermsID' => PostsTerms::selectByPostID($postID),
            'terms' => $outTerms,
            'categories' => $outCategories,
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
        $output = $delete->delete();

        if ($output) {
            Messages::addSuccess('Entrada borrada correctamente.');
        } elseif ($output === 0) {
            Messages::addWarning('La entrada no existe.');
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
            'relationshipsCategoriesID' => \filter_input(\INPUT_POST, 'relationshipsCategoriesID', \FILTER_DEFAULT, \FILTER_REQUIRE_ARRAY),
            'relationshipsTermsID' => \filter_input(\INPUT_POST, 'relationshipsTermsID', \FILTER_DEFAULT, \FILTER_REQUIRE_ARRAY),
        ];
    }

    /**
     * Metodo que actualiza las categorías vinculadas al post. Se comprueba si ha 
     * borrado y agregado categorías vinculas al post.
     * @param array $relationshipsCategoriesID Identificadores de las categorías.
     * @param int $postID Identificador del post.
     */
    private function updateRelationshipsCategories($relationshipsCategoriesID, $postID) {
        if (!empty($relationshipsCategoriesID)) {
            $postsCategories = PostsCategories::selectByPostID($postID);
            //Se combina en un unico array las nuevas categorías y las existentes.
            $merge = \array_merge($relationshipsCategoriesID, $postsCategories);
            /*
             * Se comprueba sus diferencias.
             * 
             * Al obtener la diferencias se mantienen los indices del array $MERGE
             * con array_merge se reinician los indices, evitando problemas en los modelos.
             */

            //se obtiene las categorías a eliminar
            $delete = \array_merge(\array_diff($merge, $relationshipsCategoriesID));
            //y las categorías a insertar.
            $insert = \array_merge(\array_diff($merge, $postsCategories));

            $this->deleteRelationshipsCategories($delete, $postID);
            $this->insertRelationshipsCategories($insert, $postID);
        }
    }

    /**
     * Metodo que vincula las categorías al post
     * @param array $categories Identificadores de las categorías.
     * @param int $postID Identificador del post.
     */
    private function insertRelationshipsCategories($categories, $postID) {
        if (!empty($categories)) {
            $postCategoryInsert = new PostCategoryInsert($categories, $postID);

            if (!$postCategoryInsert->insert()) {
                Messages::addError('Error al vincular las categorías.');
            }
        }
    }

    /**
     * Metodo que elimina las categorías vinculadas al post.
     * @param array $categories Identificadores de las categorías.
     * @param int $postID Identificador del post.
     */
    private function deleteRelationshipsCategories($categories, $postID) {
        if (!empty($categories)) {
            $postCategoryDelete = new PostCategoryDelete($categories, $postID);

            if (!$postCategoryDelete->delete()) {
                Messages::addError('Error al eliminar las categorías vinculadas.');
            }
        }
    }
    
    /**
     * Metodo que actualiza las etiquetas vinculadas al post. Se comprueba 
     * si se debe agregar o borrar.
     * @param array $relationshipsTermsID Identificadores de las etiquetas.
     * @param int $postID Identificador del post.
     */
    private function updateRelationshipsTerms($relationshipsTermsID, $postID) {
        if (!empty($relationshipsTermsID)) {
            $postsTerms = PostsTerms::selectByPostID($postID);
            //Se combina en un unico array.
            $merge = \array_merge($relationshipsTermsID, $postsTerms);
            /*
             * Se comprueba sus diferencias.
             * 
             * Al obtener la diferencias se mantienen los indices del array $MERGE
             * con array_merge se reinician los indices, evitando problemas en los modelos.
             */

            //se obtiene las categorías a eliminar
            $delete = \array_merge(\array_diff($merge, $relationshipsTermsID));
            //y las categorías a insertar.
            $insert = \array_merge(\array_diff($merge, $postsTerms));
            
            $this->deleteRelationshipsTerms($delete, $postID);
            $this->insertRelationshipsTerms($insert, $postID);
        }
    }

    /**
     * Metodo que vincula las etiquetas al post.
     * @param array $terms Identificadores de las etiquetas.
     * @param int $postID Identificador del post.
     */
    private function insertRelationshipsTerms($terms, $postID) {
        if (!empty($terms)) {
            $postTermInsert = new PostTermInsert($terms, $postID);

            if (!$postTermInsert->insert()) {
                Messages::addError('Error al vincular las etiquetas.');
            }
        }
    }

    /**
     * Metodo que elimina las etiquetas vinculadas al post.
     * @param array $terms Identificadores de las etiquetas.
     * @param int $postID Identificador del post.
     */
    private function deleteRelationshipsTerms($terms, $postID) {
        if (!empty($terms)) {
            $postTermDelete = new PostTermDelete($terms, $postID);

            if (!$postTermDelete->delete()) {
                Messages::addError('Error al eliminar las etiquetas vinculadas.');
            }
        }
    }

}
