<?php

/**
 * Modulo del controlador de la pagina de entradas.
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\BaseController;
use SoftnCMS\controllers\Messages;
use SoftnCMS\controllers\Pagination;
use SoftnCMS\controllers\Token;
use SoftnCMS\controllers\Form;
use SoftnCMS\Helpers\ArrayHelp;
use SoftnCMS\Helpers\Helps;
use SoftnCMS\models\admin\Post;
use SoftnCMS\models\admin\Posts;
use SoftnCMS\models\admin\PostUpdate;
use SoftnCMS\models\admin\PostInsert;
use SoftnCMS\models\admin\PostDelete;
use SoftnCMS\models\admin\Categories;
use SoftnCMS\models\admin\template\Template;
use SoftnCMS\models\admin\Terms;
use SoftnCMS\models\admin\PostCategoryInsert;
use SoftnCMS\models\admin\PostsCategories;
use SoftnCMS\models\admin\PostCategoryDelete;
use SoftnCMS\models\admin\PostsTerms;
use SoftnCMS\models\admin\PostTermInsert;
use SoftnCMS\models\admin\PostTermDelete;
use SoftnCMS\models\Login;

/**
 * Clase del controlador de la pagina de entradas.
 * @author Nicolás Marulanda P.
 */
class PostController extends BaseController {
    
    /**
     * Método llamado por la función INDEX.
     *
     * @param array $data Lista de argumentos.
     *
     * @return array
     */
    protected function dataIndex($data) {
        $output     = [];
        $countData  = Posts::count();
        $pagination = new Pagination(ArrayHelp::get($data, 'paged'), $countData);
        $limit      = $pagination->getBeginRow() . ',' . $pagination->getRowCount();
        $posts      = Posts::selectByLimit($limit);
        Template::setPagination($pagination);
        
        if ($posts !== \FALSE) {
            $output = $posts->getAll();
        }
        
        foreach ($output as $post) {
            $title = $post->getPostTitle();
            
            if (isset($title{30})) {
                $title = substr($title, 0, 30) . ' [...]';
            }
            $post->setPostTitle($title);
        }
        
        return [
            'posts' => $output,
        ];
    }
    
    /**
     * Método llamado por la función INSERT.
     * @return array
     */
    protected function dataInsert() {
        $outTerms      = [];
        $outCategories = [];
        $categories    = Categories::selectAll();
        $terms         = Terms::selectAll();
        
        if ($terms !== \FALSE) {
            $outTerms = $terms->getAll();
        }
        
        if ($categories !== \FALSE) {
            $outCategories = $categories->getAll();
        }
        
        if (Form::submit('publish')) {
            $dataInput = $this->getDataInput();
            
            if ($dataInput !== FALSE) {
                $insert = new PostInsert($dataInput['postTitle'], $dataInput['postContents'], $dataInput['commentStatus'], $dataInput['postStatus'], Login::getSession());
                
                if ($insert->insert()) {
                    Messages::addSuccess('Entrada publicada correctamente.');
                    $postID = $insert->getLastInsertId();
                    
                    $this->insertRelationshipsCategories($dataInput['relationshipsCategoriesID'], $postID);
                    $this->insertRelationshipsTerms($dataInput['relationshipsTermsID'], $postID);
                    
                    //Si es correcto se muestra el POST en la pagina de edición.
                    Helps::redirectRoute("update/$postID");
                }
            }
            Messages::addError('Error al publicar la entrada');
        }
        
        return [
            'terms'          => $outTerms,
            'categories'     => $outCategories,
            //Datos por defecto a mostrar en el formulario.
            'post'           => Post::defaultInstance(),
            'isSelectOption' => $this->isSelectOption(),
        ];
    }
    
    /**
     * Método que obtiene los datos de los campos INPUT del formulario.
     * @return array|bool
     */
    protected function getDataInput() {
        if (Token::check()) {
            Form::addInputAlphanumeric('postTitle');
            Form::addInputHtml('postContents');
            Form::addInputBoolean('commentStatus', TRUE);
            Form::addInputBoolean('postStatus', TRUE);
            Form::addInputArrayList('relationshipsCategoriesID');
            Form::addInputArrayList('relationshipsTermsID');
            
            return Form::postInput();
        }
        
        return FALSE;
    }
    
    /**
     * Método que vincula las categorías al post
     *
     * @param array $categories Identificadores de las categorías.
     * @param int   $postID     Identificador del post.
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
     * Método que vincula las etiquetas al post.
     *
     * @param array $terms  Identificadores de las etiquetas.
     * @param int   $postID Identificador del post.
     */
    private function insertRelationshipsTerms($terms, $postID) {
        if (!empty($terms)) {
            $postTermInsert = new PostTermInsert($terms, $postID);
            
            if (!$postTermInsert->insert()) {
                Messages::addError('Error al vincular las etiquetas.');
            }
        }
    }
    
    private function isSelectOption() {
        return function($value, $array, $key) {
            if (is_array($array) && array_key_exists($key, $array) && is_array($array[$key]) && in_array($value, $array[$key])) {
                return TRUE;
            }
            
            return FALSE;
        };
    }
    
    /**
     * Método llamado por la función UPDATE.
     *
     * @param array $data Lista de argumentos.
     *
     * @return array
     */
    protected function dataUpdate($data) {
        $id   = ArrayHelp::get($data, 'id');
        $post = Post::selectByID($id);
        
        //En caso de que no exista.
        if (empty($post)) {
            Messages::addError('Error. La entrada no existe.');
            Helps::redirectRoute();
        }
        
        $outTerms      = [];
        $outCategories = [];
        $categories    = Categories::selectAll();
        $terms         = Terms::selectAll();
        
        if ($terms !== \FALSE) {
            $outTerms = $terms->getAll();
        }
        
        if ($categories !== \FALSE) {
            $outCategories = $categories->getAll();
        }
        
        if (Form::submit('update')) {
            $dataInput = $this->getDataInput();
            
            if ($dataInput === FALSE) {
                Messages::addError('Error al actualizar la entrada.');
            } else {
                $update = new PostUpdate($post, $dataInput['postTitle'], $dataInput['postContents'], $dataInput['commentStatus'], $dataInput['postStatus']);
                
                //Si ocurre un error la función "$update->update()" retorna FALSE.
                if ($update->update()) {
                    Messages::addSuccess('Entrada actualizada correctamente.');
                    $post = $update->getDataUpdate();
                    
                    $this->updateRelationshipsCategories($dataInput['relationshipsCategoriesID'], $id);
                    $this->updateRelationshipsTerms($dataInput['relationshipsTermsID'], $id);
                } else {
                    Messages::addError('Error al actualizar la entrada.');
                }
            }
        }
        
        return [
            'relationshipsCategoriesID' => PostsCategories::selectByPostID($id),
            'relationshipsTermsID'      => PostsTerms::selectByPostID($id),
            'terms'                     => $outTerms,
            'categories'                => $outCategories,
            //Instancia POST
            'post'                      => $post,
            'isSelectOption'            => $this->isSelectOption(),
        ];
    }
    
    /**
     * Método que actualiza las categorías vinculadas al post. Se comprueba si ha
     * borrado y agregado categorías vinculas al post.
     *
     * @param array $relationshipsCategoriesID Identificadores de las categorías.
     * @param int   $postID                    Identificador del post.
     */
    private function updateRelationshipsCategories($relationshipsCategoriesID, $postID) {
        $postsCategories = PostsCategories::selectByPostID($postID);
        
        //Si no se ha seleccionado nada se borra todos los datos vinculados.
        if (empty($relationshipsCategoriesID)) {
            $this->deleteRelationshipsCategories($postsCategories, $postID);
        } else {
            
            if ($postsCategories === \FALSE) {
                $postsCategories = [];
            }
            
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
     * Método que elimina las categorías vinculadas al post.
     *
     * @param array $categories Identificadores de las categorías.
     * @param int   $postID     Identificador del post.
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
     * Método que actualiza las etiquetas vinculadas al post. Se comprueba
     * si se debe agregar o borrar.
     *
     * @param array $relationshipsTermsID Identificadores de las etiquetas.
     * @param int   $postID               Identificador del post.
     */
    private function updateRelationshipsTerms($relationshipsTermsID, $postID) {
        $postsTerms = PostsTerms::selectByPostID($postID);
        
        //Si no se ha seleccionado nada se borra todos los datos vinculados.
        if (empty($relationshipsTermsID)) {
            $this->deleteRelationshipsTerms($postsTerms, $postID);
        } else {
            
            if ($postsTerms === \FALSE) {
                $postsTerms = [];
            }
            
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
     * Método que elimina las etiquetas vinculadas al post.
     *
     * @param array $terms  Identificadores de las etiquetas.
     * @param int   $postID Identificador del post.
     */
    private function deleteRelationshipsTerms($terms, $postID) {
        if (!empty($terms)) {
            $postTermDelete = new PostTermDelete($terms, $postID);
            
            if (!$postTermDelete->delete()) {
                Messages::addError('Error al eliminar las etiquetas vinculadas.');
            }
        }
    }
    
    /**
     * Método llamado por la función DELETE.
     *
     * @param array $data Lista de argumentos.
     */
    protected function dataDelete($data) {
        /*
         * Ya que este método no tiene modulo vista propia
         * se carga el modulo vista INDEX, asi que se retornan los datos
         * para esta vista.
         */
        
        $output = FALSE;
        
        if (Token::check()) {
            $delete = new PostDelete($data['id']);
            $output = $delete->delete();
        }
        
        if ($output) {
            Messages::addSuccess('Entrada borrada correctamente.');
        } elseif ($output === 0) {
            Messages::addWarning('La entrada no existe.');
        } else {
            Messages::addError('Error al borrar la entrada.');
        }
        
    }
    
}
