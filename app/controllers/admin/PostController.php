<?php
/**
 * PostController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\classes\constants\Constants;
use SoftnCMS\models\managers\CategoriesManager;
use SoftnCMS\models\managers\LoginManager;
use SoftnCMS\models\managers\PostsCategoriesManager;
use SoftnCMS\models\managers\PostsManager;
use SoftnCMS\models\managers\PostsTermsManager;
use SoftnCMS\models\managers\TermsManager;
use SoftnCMS\models\managers\UsersManager;
use SoftnCMS\models\tables\Post;
use SoftnCMS\models\tables\PostCategory;
use SoftnCMS\models\tables\PostTerm;
use SoftnCMS\rute\Router;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\controller\ControllerAbstract;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputBooleanBuilder;
use SoftnCMS\util\form\builders\InputHtmlBuilder;
use SoftnCMS\util\form\builders\InputIntegerBuilder;
use SoftnCMS\util\form\builders\InputListIntegerBuilder;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Token;
use SoftnCMS\util\Util;

/**
 * Class PostController
 * @author Nicolás Marulanda P.
 */
class PostController extends ControllerAbstract {
    
    public function index() {
        $postsManager = new PostsManager($this->getConnectionDB());
        $count        = $postsManager->count();
        
        $this->sendDataView([
            'posts' => $postsManager->searchAll($this->rowsPages($count)),
        ]);
        $this->view();
    }
    
    public function create() {
        if ($this->checkSubmit(Constants::FORM_CREATE)) {
            if ($this->isValidForm()) {
                $postsManager = new PostsManager($this->getConnectionDB());
                $post         = $this->getForm('post');
                
                if ($postsManager->create($post)) {
                    $usersManager = new UsersManager($this->getConnectionDB());
                    $postId       = $postsManager->getLastInsertId();
                    $terms        = $this->getForm('terms'); //Etiquetas nuevas
                    $categories   = $this->getForm('categories'); //Categorías nuevas
                    $usersManager->updatePostCount($post->getUserId(), 1);
                    $this->createOrDeleteTerms($terms, $postId);
                    $this->createOrDeleteCategories($categories, $postId);
                    Messages::addSuccess(__('Entrada publicada correctamente.'), TRUE);
                    $this->redirectToAction('index');
                }
            }
            
            Messages::addDanger(__('Error al publicar la entrada.'));
        }
        
        $this->sendViewCategoriesAndTerms();
        $this->sendViewUsers();
        $this->sendDataView([
            'isUpdate'       => FALSE,
            'selectedUserId' => LoginManager::getUserId(),
            'post'           => new Post(),
            'title'          => __('Publicar nueva entrada'),
        ]);
        $this->view('form');
    }
    
    private function createOrDeleteTerms($termsId, $postId) {
        $postsTermsManager = new PostsTermsManager($this->getConnectionDB());
        $currentTermsId    = $this->getCurrentTermsId($postId);
        
        if (empty($termsId)) {
            if ($postsTermsManager->deleteAllByPostId($postId) === FALSE) {
                Messages::addDanger(__('Error al borrar las etiquetas.'));
            }
        } else {
            $numError = 0;
            //Obtengo los identificadores de las nuevas etiquetas.
            $newTerms = array_filter($termsId, function($termId) use ($currentTermsId) {
                return !Arrays::valueExists($currentTermsId, $termId);
            });
            $newTerms = array_map(function($value) use ($postId) {
                $object = new PostTerm();
                $object->setTermId($value);
                $object->setPostId($postId);
                
                return $object;
            }, $newTerms);
            
            //Obtengo los identificadores de las etiquetas que no se han seleccionado.
            $termsIdNotSelected = array_filter($currentTermsId, function($value) use ($termsId) {
                return !Arrays::valueExists($termsId, $value);
            });
            
            array_walk($termsIdNotSelected, function($termId) use ($postId, $postsTermsManager, &$numError) {
                if (!$postsTermsManager->deleteByPostAndTerm($postId, $termId)) {
                    $numError++;
                }
            });
            
            array_walk($newTerms, function(PostTerm $postTerm) use ($postsTermsManager, &$numError) {
                if ($postsTermsManager->create($postTerm) === FALSE) {
                    $numError++;
                }
            });
            
            if ($numError > 0) {
                Messages::addDanger(__('Error al actualizar las etiquetas.'));
            }
        }
    }
    
    private function getCurrentTermsId($postId) {
        $postsTermsManager = new PostsTermsManager($this->getConnectionDB());
        $postTerms         = $postsTermsManager->searchAllByPostId($postId); //Etiquetas actuales
        $currentTermsId    = array_map(function(PostTerm $value) {
            return $value->getTermId();
        }, $postTerms);
        
        return $currentTermsId;
    }
    
    private function createOrDeleteCategories($categoriesId, $postId) {
        $postsCategoriesManager = new PostsCategoriesManager($this->getConnectionDB());
        $currentCategoriesId    = $this->getCurrentCategoriesId($postId);
        
        if (empty($categoriesId)) {
            if ($postsCategoriesManager->deleteAllByPostId($postId) === FALSE) {
                Messages::addDanger(__('Error al borrar las categorías.'));
            }
        } else {
            $numError = 0;
            //Obtengo los identificadores de las nuevas categorías.
            $newCategories = array_filter($categoriesId, function($value) use ($currentCategoriesId) {
                return !Arrays::valueExists($currentCategoriesId, $value);
            });
            $newCategories = array_map(function($value) use ($postId) {
                $object = new PostCategory();
                $object->setCategoryId($value);
                $object->setPostId($postId);
                
                return $object;
            }, $newCategories);
            
            //Obtengo los identificadores de las categorías que no se han seleccionado.
            $CategoriesIdNotSelected = array_filter($currentCategoriesId, function($value) use ($categoriesId) {
                return !Arrays::valueExists($categoriesId, $value);
            });
            
            array_walk($CategoriesIdNotSelected, function($categoryId) use ($postId, $postsCategoriesManager, &$numError) {
                if (!$postsCategoriesManager->deleteByPostAndCategory($postId, $categoryId)) {
                    $numError++;
                }
            });
            
            array_walk($newCategories, function(PostCategory $postCategory) use ($postsCategoriesManager, &$numError) {
                if ($postsCategoriesManager->create($postCategory) === FALSE) {
                    $numError++;
                }
            });
            
            if ($numError > 0) {
                Messages::addDanger(__('Error al actualizar las categorías.'));
            }
        }
    }
    
    private function getCurrentCategoriesId($postId) {
        $postsCategoriesManager = new PostsCategoriesManager($this->getConnectionDB());
        $postCategories         = $postsCategoriesManager->searchAllByPostId($postId); //Categorías actuales
        $CurrentCategoriesId    = array_map(function(PostCategory $value) {
            return $value->getCategoryId();
        }, $postCategories);
        
        return $CurrentCategoriesId;
    }
    
    private function sendViewCategoriesAndTerms() {
        $termsManager      = new TermsManager($this->getConnectionDB());
        $categoriesManager = new CategoriesManager($this->getConnectionDB());
        $categories        = $categoriesManager->searchAll();
        $terms             = $termsManager->searchAll();
        
        $this->sendDataView([
            'categories' => $categories,
            'terms'      => $terms,
        ]);
    }
    
    private function sendViewUsers() {
        $usersManager = new UsersManager($this->getConnectionDB());
        $this->sendDataView(['usersList' => $usersManager->searchAll()]);
    }
    
    public function update($id) {
        $postsManager = new PostsManager($this->getConnectionDB());
        $post         = $postsManager->searchById($id);
        
        if (empty($post)) {
            Messages::addDanger(__('La publicación no existe.'), TRUE);
            $this->redirectToAction('index');
        } elseif ($this->checkSubmit(Constants::FORM_UPDATE)) {
            if ($this->isValidForm()) {
                $post = $this->getForm('post');
                
                if ($postsManager->update($post)) {
                    $post       = $postsManager->searchById($id);
                    $terms      = $this->getForm('terms'); //Etiquetas nuevas
                    $categories = $this->getForm('categories'); //Categorías nuevas
                    $this->createOrDeleteTerms($terms, $id);
                    $this->createOrDeleteCategories($categories, $id);
                    Messages::addSuccess(__('Entrada actualizada correctamente.'));
                } else {
                    Messages::addDanger(__('Error al actualizar la entrada.'));
                }
            } else {
                Messages::addDanger(__('Error en los campos de la entrada.'));
            }
        }
        
        $this->sendViewCategoriesAndTerms();
        $this->sendViewUsers();
        $this->sendDataView([
            'isUpdate'             => TRUE,
            'selectedUserId'       => $post->getUserId(),
            'linkPost'             => Router::getSiteURL() . "post/$id",
            'selectedCategoriesId' => $this->getCurrentCategoriesId($id),
            'selectedTermsId'      => $this->getCurrentTermsId($id),
            'post'                 => $post,
            'title'                => __('Actualizar entrada'),
        ]);
        $this->view('form');
    }
    
    public function delete($id) {
        if (Token::check()) {
            $postsManager = new PostsManager($this->getConnectionDB());
            $result       = $postsManager->deleteById($id);
            $rowCount     = $postsManager->getRowCount();
            
            if ($rowCount === 0) {
                Messages::addWarning(__('La publicación no existe.'), TRUE);
            } elseif ($result) {
                Messages::addSuccess(__('Entrada borrada correctamente.'), TRUE);
            } else {
                Messages::addDanger(__('Error al borrar la entrada.'), TRUE);
            }
        }
        
        $this->redirectToAction('index');
    }
    
    protected function formToObject() {
        $post       = new Post();
        $date       = Util::dateNow();
        $terms      = $this->getInput(PostsTermsManager::TERM_ID);
        $categories = $this->getInput(PostsCategoriesManager::CATEGORY_ID);
        $post->setId($this->getInput(PostsManager::COLUMN_ID));
        $post->setPostCommentCount(NULL);
        $post->setPostDate(NULL);
        $post->setPostUpdate($date);
        $post->setPostTitle($this->getInput(PostsManager::POST_TITLE));
        $post->setPostStatus($this->getInput(PostsManager::POST_STATUS));
        $post->setPostCommentStatus($this->getInput(PostsManager::POST_COMMENT_STATUS));
        $post->setPostContents($this->getInput(PostsManager::POST_CONTENTS));
        $post->setUserId($this->getInput(PostsManager::USER_ID));
        
        if ($this->checkSubmit(Constants::FORM_CREATE)) {
            $post->setPostCommentCount(0);
            $post->setPostDate($date);
        }
        
        return [
            'post'       => $post,
            'categories' => $categories,
            'terms'      => $terms,
        ];
    }
    
    protected function formInputsBuilders() {
        return [
            InputIntegerBuilder::init(PostsManager::COLUMN_ID)
                               ->build(),
            InputAlphanumericBuilder::init(PostsManager::POST_TITLE)
                                    ->setSpecialChar(TRUE)
                                    ->build(),
            InputHtmlBuilder::init(PostsManager::POST_CONTENTS)
                            ->build(),
            InputBooleanBuilder::init(PostsManager::POST_COMMENT_STATUS)
                               ->build(),
            InputBooleanBuilder::init(PostsManager::POST_STATUS)
                               ->build(),
            InputListIntegerBuilder::init(PostsCategoriesManager::CATEGORY_ID)
                                   ->setRequire(FALSE)
                                   ->build(),
            InputListIntegerBuilder::init(PostsTermsManager::TERM_ID)
                                   ->setRequire(FALSE)
                                   ->build(),
            InputIntegerBuilder::init(PostsManager::USER_ID)
                               ->build(),
        ];
    }
    
}
