<?php
/**
 * PostController.phproller.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\CUDControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\managers\CategoriesManager;
use SoftnCMS\models\managers\LoginManager;
use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\models\managers\PostsCategoriesManager;
use SoftnCMS\models\managers\PostsManager;
use SoftnCMS\models\managers\PostsTermsManager;
use SoftnCMS\models\managers\TermsManager;
use SoftnCMS\models\managers\UsersManager;
use SoftnCMS\models\tables\Post;
use SoftnCMS\models\tables\PostCategory;
use SoftnCMS\models\tables\PostTerm;
use SoftnCMS\models\tables\Term;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputBooleanBuilder;
use SoftnCMS\util\form\builders\InputHtmlBuilder;
use SoftnCMS\util\form\builders\InputIntegerBuilder;
use SoftnCMS\util\form\builders\InputListIntegerBuilder;
use SoftnCMS\util\form\Form;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Util;

/**
 * Class PostController
 * @author Nicolás Marulanda P.
 */
class PostController extends CUDControllerAbstract {
    
    public function create() {
        $showForm = TRUE;
        
        if (Form::submit(CRUDManagerAbstract::FORM_CREATE)) {
            $postsManager = new PostsManager();
            $messages     = 'Error al publicar la entrada.';
            $typeMessage  = Messages::TYPE_DANGER;
            $form         = $this->form();
            
            if (!empty($form)) {
                $post = Arrays::get($form, 'post');
                
                if ($postsManager->create($post)) {
                    $usersManager   = new UsersManager();
                    $optionsManager = new OptionsManager();
                    $showForm       = FALSE;
                    $messages       = 'Entrada publicada correctamente.';
                    $typeMessage    = Messages::TYPE_SUCCESS;
                    $postId         = $postsManager->getLastInsertId();
                    $terms          = Arrays::get($form, 'terms'); //Etiquetas nuevas
                    $categories     = Arrays::get($form, 'categories'); //Categorías nuevas
                    $usersManager->updatePostCount($post->getUserID(), 1);
                    $this->createOrDeleteTerms($terms, $postId);
                    $this->createOrDeleteCategories($categories, $postId);
                    Messages::addSessionMessage($messages, $typeMessage);
                    Util::redirect($optionsManager->getSiteUrl() . 'admin/post');
                }
            }
            
            Messages::addMessage($messages, $typeMessage);
        }
        
        if ($showForm) {
            $this->sendViewCategoriesAndTerms();
            ViewController::sendViewData('post', new Post());
            ViewController::sendViewData('title', 'Publicar nueva entrada');
            ViewController::view('form');
        }
    }
    
    protected function form() {
        $inputs = $this->filterInputs();
        
        if (empty($inputs)) {
            return FALSE;
        }
        
        $post       = new Post();
        $date       = Util::dateNow();
        $terms      = Arrays::get($inputs, PostsTermsManager::TERM_ID);
        $categories = Arrays::get($inputs, PostsCategoriesManager::CATEGORY_ID);
        $post->setId(Arrays::get($inputs, PostsManager::ID));
        $post->setCommentCount(NULL);
        $post->setPostDate(NULL);
        $post->setPostUpdate($date);
        $post->setPostTitle(Arrays::get($inputs, PostsManager::POST_TITLE));
        $post->setPostStatus(Arrays::get($inputs, PostsManager::POST_STATUS));
        $post->setCommentStatus(Arrays::get($inputs, PostsManager::POST_COMMENT_STATUS));
        $post->setPostContents(Arrays::get($inputs, PostsManager::POST_CONTENTS));
        $post->setUserID(NULL);
        
        if (Form::submit(CRUDManagerAbstract::FORM_CREATE)) {
            $post->setUserID(LoginManager::getSession());
            $post->setCommentCount(0);
            $post->setPostDate($date);
        }
        
        return [
            'post'       => $post,
            'categories' => $categories,
            'terms'      => $terms,
        ];
    }
    
    protected function filterInputs() {
        Form::setINPUT([
            InputIntegerBuilder::init(PostsManager::ID)
                               ->build(),
            InputAlphanumericBuilder::init(PostsManager::POST_TITLE)
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
        ]);
        
        return Form::inputFilter();
    }
    
    private function createOrDeleteTerms($termsId, $postId) {
        $typeMessage       = Messages::TYPE_DANGER;
        $postsTermsManager = new PostsTermsManager();
        $currentTermsId    = $this->getCurrentTermsId($postId);
        
        if (empty($termsId)) {
            if ($postsTermsManager->deleteAllByPostId($postId) === FALSE) {
                $message = 'Error al borrar las etiquetas.';
                Messages::addMessage($message, $typeMessage);
            }
            
        } else {
            $termsManager = new TermsManager();
            $numError     = 0;
            //Obtengo los identificadores de las nuevas etiquetas.
            $newTerms = array_filter($termsId, function($termId) use ($currentTermsId) {
                return !Arrays::valueExists($currentTermsId, $termId);
            });
            $newTerms = array_map(function($value) use ($postId) {
                $object = new PostTerm();
                $object->setTermID($value);
                $object->setPostID($postId);
                
                return $object;
            }, $newTerms);
            
            //Obtengo los identificadores de las etiquetas que no se han seleccionado.
            $termsIdNotSelected = array_filter($currentTermsId, function($value) use ($termsId) {
                return !Arrays::valueExists($termsId, $value);
            });
            
            array_walk($termsIdNotSelected, function($termId) use ($postId, $postsTermsManager, &$numError, $termsManager) {
                if (!$postsTermsManager->deleteByPostAndTerm($postId, $termId) || !$termsManager->updateCount($termId, -1)) {
                    $numError++;
                }
            });
            
            array_walk($newTerms, function(PostTerm $postTerm) use ($postsTermsManager, &$numError, $termsManager) {
                if (!$postsTermsManager->create($postTerm) || !$termsManager->updateCount($postTerm->getTermID(), 1)) {
                    $numError++;
                }
            });
            
            if ($numError > 0) {
                Messages::addMessage('Error al actualizar las etiquetas.', Messages::TYPE_DANGER);
            }
        }
    }
    
    private function getCurrentTermsId($postId) {
        $postsTermsManager = new PostsTermsManager();
        $postTerms         = $postsTermsManager->searchAllByPostId($postId); //Etiquetas actuales
        $currentTermsId    = array_map(function(PostTerm $value) {
            return $value->getTermID();
        }, $postTerms);
        
        return $currentTermsId;
    }
    
    private function createOrDeleteCategories($categoriesId, $postId) {
        $typeMessage            = Messages::TYPE_DANGER;
        $postsCategoriesManager = new PostsCategoriesManager();
        $currentCategoriesId    = $this->getCurrentCategoriesId($postId);
        
        if (empty($categoriesId)) {
            if ($postsCategoriesManager->deleteAllByPostId($postId) === FALSE) {
                $message = 'Error al borrar las categorías.';
                Messages::addMessage($message, $typeMessage);
            }
            
        } else {
            $categoriesManager = new CategoriesManager();
            $numError          = 0;
            //Obtengo los identificadores de las nuevas categorías.
            $newCategories = array_filter($categoriesId, function($value) use ($currentCategoriesId) {
                return !Arrays::valueExists($currentCategoriesId, $value);
            });
            $newCategories = array_map(function($value) use ($postId) {
                $object = new PostCategory();
                $object->setCategoryID($value);
                $object->setPostID($postId);
                
                return $object;
            }, $newCategories);
            
            //Obtengo los identificadores de las categorías que no se han seleccionado.
            $CategoriesIdNotSelected = array_filter($currentCategoriesId, function($value) use ($categoriesId) {
                return !Arrays::valueExists($categoriesId, $value);
            });
            
            array_walk($CategoriesIdNotSelected, function($categoryId) use ($postId, $postsCategoriesManager, &$numError, $categoriesManager) {
                if (!$postsCategoriesManager->deleteByPostAndCategory($postId, $categoryId) || !$categoriesManager->updateCount($categoryId, -1)) {
                    $numError++;
                }
            });
            
            array_walk($newCategories, function(PostCategory $postCategory) use ($postsCategoriesManager, &$numError, $categoriesManager) {
                if (!$postsCategoriesManager->create($postCategory) || !$categoriesManager->updateCount($postCategory->getCategoryID(), 1)) {
                    $numError++;
                }
            });
            
            if ($numError > 0) {
                Messages::addMessage('Error al actualizar las categorías.', Messages::TYPE_DANGER);
            }
        }
    }
    
    private function getCurrentCategoriesId($postId) {
        $postsCategoriesManager = new PostsCategoriesManager();
        $postCategories         = $postsCategoriesManager->searchAllByPostId($postId); //Categorías actuales
        $CurrentCategoriesId    = array_map(function(PostCategory $value) {
            return $value->getCategoryID();
        }, $postCategories);
        
        return $CurrentCategoriesId;
    }
    
    private function sendViewCategoriesAndTerms() {
        $termsManager      = new TermsManager();
        $categoriesManager = new CategoriesManager();
        $categories        = $categoriesManager->read();
        $terms             = $termsManager->read();
        
        ViewController::sendViewData('categories', $categories);
        ViewController::sendViewData('terms', $terms);
    }
    
    public function update($id) {
        $typeMessage  = Messages::TYPE_DANGER;
        $messages     = 'La entrada no existe.';
        $postsManager = new PostsManager();
        $post         = $postsManager->searchById($id);
        
        if (empty($post)) {
            Messages::addMessage($messages, $typeMessage);
            $this->index();
        } else {
            if (Form::submit(CRUDManagerAbstract::FORM_UPDATE)) {
                $messages = 'Error al actualizar la entrada.';
                $form     = $this->form();
                
                if (!empty($form)) {
                    $post = Arrays::get($form, 'post');
                    
                    if ($postsManager->update($post)) {
                        $post        = $postsManager->searchById($id);
                        $messages    = 'Entrada actualizada correctamente.';
                        $typeMessage = Messages::TYPE_SUCCESS;
                        $terms       = Arrays::get($form, 'terms'); //Etiquetas nuevas
                        $categories  = Arrays::get($form, 'categories'); //Categorías nuevas
                        $this->createOrDeleteTerms($terms, $id);
                        $this->createOrDeleteCategories($categories, $id);
                    }
                }
                
                Messages::addMessage($messages, $typeMessage);
            }
            
            $optionsManager       = new OptionsManager();
            $linkPost             = $optionsManager->getSiteUrl() . 'post/' . $id;
            $selectedCategoriesId = $this->getCurrentCategoriesId($id);
            $selectedTermsId      = $this->getCurrentTermsId($id);
            $this->sendViewCategoriesAndTerms();
            ViewController::sendViewData('linkPost', $linkPost);
            ViewController::sendViewData('selectedCategoriesId', $selectedCategoriesId);
            ViewController::sendViewData('selectedTermsId', $selectedTermsId);
            ViewController::sendViewData('post', $post);
            ViewController::sendViewData('title', 'Actualizar entrada');
            ViewController::view('form');
        }
    }
    
    public function index() {
        $this->read();
        ViewController::view('index');
    }
    
    protected function read() {
        $filters      = [];
        $postsManager = new PostsManager();
        $count        = $postsManager->count();
        $pagination   = parent::pagination($count);
        
        if ($pagination !== FALSE) {
            $filters['limit'] = $pagination;
        }
        
        ViewController::sendViewData('posts', $postsManager->read($filters));
    }
    
    public function delete($id) {
        $messages     = 'Error al borrar la entrada.';
        $typeMessage  = Messages::TYPE_DANGER;
        $postsManager = new PostsManager();
        $usersManager = new UsersManager();
        $user         = $usersManager->searchByPostId($id);
        
        if (!empty($postsManager->delete($id))) {
            $usersManager->updatePostCount($user->getId(), -1);
            $messages    = 'Entrada borrada correctamente.';
            $typeMessage = Messages::TYPE_SUCCESS;
        }
        
        Messages::addMessage($messages, $typeMessage);
        parent::delete($id);
    }
    
}
