<?php
/**
 * PostController.phproller.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\CUDControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\ManagerAbstract;
use SoftnCMS\models\managers\CategoriesManager;
use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\models\managers\PostsCategoriesManager;
use SoftnCMS\models\managers\PostsManager;
use SoftnCMS\models\managers\PostsTermsManager;
use SoftnCMS\models\managers\TermsManager;
use SoftnCMS\models\tables\Post;
use SoftnCMS\models\tables\PostCategory;
use SoftnCMS\models\tables\PostTerm;
use SoftnCMS\models\tables\Term;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\form\builders\InputAlphabeticBuilder;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputBooleanBuilder;
use SoftnCMS\util\form\builders\InputHtmlBuilder;
use SoftnCMS\util\form\builders\InputIntegerBuilder;
use SoftnCMS\util\form\builders\InputListIntegerBuilder;
use SoftnCMS\util\form\Form;
use SoftnCMS\util\form\InputAlphabetic;
use SoftnCMS\util\form\InputListInteger;
use SoftnCMS\util\form\inputs\builders\InputNumberBuilder;
use SoftnCMS\util\form\inputs\builders\InputTextBuilder;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Pagination;
use SoftnCMS\util\Util;

/**
 * Class PostController
 * @author Nicolás Marulanda P.
 */
class PostController extends CUDControllerAbstract {
    
    public function create() {
        $result = FALSE;
        
        if (Arrays::get($_POST, PostsManager::FORM_CREATE)) {
            $postsManager = new PostsManager();
            $messages     = 'Error al publicar la entrada.';
            $typeMessage  = Messages::TYPE_DANGER;
            $form         = $this->form();
            
            if (!empty($form)) {
                $post   = Arrays::get($form, 'post');
                $result = $postsManager->create($post);
                
                if ($result) {
                    $messages    = 'Entrada publicada correctamente.';
                    $typeMessage = Messages::TYPE_SUCCESS;
                    $postId      = $postsManager->getLastInsertId();
                    $terms       = Arrays::get($form, 'terms'); //Etiquetas nuevas
                    $categories  = Arrays::get($form, 'categories'); //Categorías nuevas
                    $this->createOrDeleteTerms($terms, $postId);
                    $this->createOrDeleteCategories($categories, $postId);
                    Messages::addMessage($messages, $typeMessage);
                    //TODO: temporalmente, hasta crear una redirección a la pagina "update".
                    $this->index();
                }
            }
            
            Messages::addMessage($messages, $typeMessage);
        }
        
        if (!$result) {
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
        $isUpdate   = Arrays::get($inputs, PostsManager::FORM_UPDATE);
        $terms      = Arrays::get($inputs, PostsTermsManager::TERM_ID);
        $categories = Arrays::get($inputs, PostsCategoriesManager::CATEGORY_ID);
        
        $post->setId(Arrays::get($inputs, PostsManager::ID));
        $post->setCommentCount(NULL);
        $post->setPostDate(NULL);
        $post->setPostUpdate($date);
        $post->setPostTitle(Arrays::get($inputs, PostsManager::POST_TITLE));
        $post->setPostStatus(Arrays::get($inputs, PostsManager::POST_STATUS));
        $post->setCommentStatus(Arrays::get($inputs, PostsManager::COMMENT_STATUS));
        $post->setPostContents(Arrays::get($inputs, PostsManager::POST_CONTENTS));
        //TODO: temporalmente establecido al id 1.
        $post->setUserID(1);
        
        if (empty($isUpdate)) {
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
            InputAlphabeticBuilder::init(PostsManager::FORM_UPDATE)
                                  ->setRequire(FALSE)
                                  ->setAccents(FALSE)
                                  ->build(),
            InputIntegerBuilder::init(PostsManager::ID)
                               ->build(),
            InputAlphanumericBuilder::init(PostsManager::POST_TITLE)
                                    ->build(),
            InputHtmlBuilder::init(PostsManager::POST_CONTENTS)
                            ->build(),
            InputBooleanBuilder::init(PostsManager::COMMENT_STATUS)
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
        $selectedTermsId   = $this->getSelectedTermsId($postId);
        
        if (empty($termsId)) {
            if ($postsTermsManager->deleteAllByPostId($postId) === FALSE) {
                $message = 'Error al borrar las etiquetas.';
                Messages::addMessage($message, $typeMessage);
            }
            
        } else {
            $numError = 0;
            //Obtengo los identificadores de las nuevas etiquetas.
            $newTerms = array_filter($termsId, function($value) use ($selectedTermsId) {
                return !Arrays::valueExists($selectedTermsId, $value);
            });
            $newTerms = array_map(function($value) use ($postId) {
                $object = new PostTerm();
                $object->setTermID($value);
                $object->setPostID($postId);
                
                return $object;
            }, $newTerms);
            
            //Obtengo los identificadores de las etiquetas que no se han seleccionado.
            $termsNotSelected = array_filter($selectedTermsId, function($value) use ($termsId) {
                return !Arrays::valueExists($termsId, $value);
            });
            
            array_walk($termsNotSelected, function($value) use ($postId, $postsTermsManager, &$numError) {
                if ($postsTermsManager->deleteByPostAndTerm($postId, $value) === FALSE) {
                    $numError++;
                }
            });
            
            array_walk($newTerms, function($value) use ($postsTermsManager, &$numError) {
                if (!$postsTermsManager->create($value)) {
                    $numError++;
                }
            });
            
            if ($numError > 0) {
                Messages::addMessage('Error al actualizar las etiquetas.', Messages::TYPE_DANGER);
            }
        }
    }
    
    private function getSelectedTermsId($postId) {
        $postsTermsManager = new PostsTermsManager();
        $postTerms         = $postsTermsManager->searchAllByPostId($postId); //Etiquetas actuales
        $selectedTermsId   = array_map(function($value) {
            return $value->getTermID();
        }, $postTerms);
        
        return $selectedTermsId;
    }
    
    private function createOrDeleteCategories($categoriesId, $postId) {
        $typeMessage            = Messages::TYPE_DANGER;
        $postsCategoriesManager = new PostsCategoriesManager();
        $selectedCategoriesId   = $this->getSelectedCategoriesId($postId);
        
        if (empty($categoriesId)) {
            if ($postsCategoriesManager->deleteAllByPostId($postId) === FALSE) {
                $message = 'Error al borrar las categorías.';
                Messages::addMessage($message, $typeMessage);
            }
            
        } else {
            $numError = 0;
            //Obtengo los identificadores de las nuevas categorías.
            $newCategories = array_filter($categoriesId, function($value) use ($selectedCategoriesId) {
                return !Arrays::valueExists($selectedCategoriesId, $value);
            });
            $newCategories = array_map(function($value) use ($postId) {
                $object = new PostCategory();
                $object->setCategoryID($value);
                $object->setPostID($postId);
                
                return $object;
            }, $newCategories);
            
            //Obtengo los identificadores de las categorías que no se han seleccionado.
            $CategoriesNotSelected = array_filter($selectedCategoriesId, function($value) use ($categoriesId) {
                return !Arrays::valueExists($categoriesId, $value);
            });
            
            array_walk($CategoriesNotSelected, function($value) use ($postId, $postsCategoriesManager, &$numError) {
                if ($postsCategoriesManager->deleteByPostAndCategory($postId, $value) === FALSE) {
                    $numError++;
                }
            });
            
            array_walk($newCategories, function($value) use ($postsCategoriesManager, &$numError) {
                if (!$postsCategoriesManager->create($value)) {
                    $numError++;
                }
            });
            
            if ($numError > 0) {
                Messages::addMessage('Error al actualizar las categorías.', Messages::TYPE_DANGER);
            }
        }
    }
    
    private function getSelectedCategoriesId($postId) {
        $postsCategoriesManager = new PostsCategoriesManager();
        $postCategories         = $postsCategoriesManager->searchAllByPostId($postId); //Categorías actuales
        $selectedCategoriesId   = array_map(function($value) {
            return $value->getCategoryID();
        }, $postCategories);
        
        return $selectedCategoriesId;
    }
    
    public function index() {
        $this->read();
        ViewController::view('index');
    }
    
    protected function read() {
        $filters      = [];
        $postsManager = new PostsManager();
        $count        = $postsManager->count();
        $postsManager = new PostsManager();
        $pagination   = parent::pagination($count);
        
        if ($pagination !== FALSE) {
            $filters['limit'] = $pagination;
        }
        
        ViewController::sendViewData('posts', $postsManager->read($filters));
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
            
            $optionsManager = new OptionsManager();
            
            if (Arrays::get($_POST, PostsManager::FORM_UPDATE)) {
                $messages = 'Error al actualizar la entrada.';
                $form     = $this->form();
                $post     = Arrays::get($form, 'post');
                
                if ($postsManager->update($post)) {
                    $post        = $postsManager->searchById($id);
                    $messages    = 'Entrada actualizada correctamente.';
                    $typeMessage = Messages::TYPE_SUCCESS;
                    $terms       = Arrays::get($form, 'terms'); //Etiquetas nuevas
                    $categories  = Arrays::get($form, 'categories'); //Categorías nuevas
                    $this->createOrDeleteTerms($terms, $id);
                    $this->createOrDeleteCategories($categories, $id);
                }
                
                Messages::addMessage($messages, $typeMessage);
            }
            
            $linkPost             = $optionsManager->getSiteUrl() . 'post/' . $id;
            $selectedCategoriesId = $this->getSelectedCategoriesId($id);
            $selectedTermsId      = $this->getSelectedTermsId($id);
            $this->sendViewCategoriesAndTerms();
            ViewController::sendViewData('linkPost', $linkPost);
            ViewController::sendViewData('selectedCategoriesId', $selectedCategoriesId);
            ViewController::sendViewData('selectedTermsId', $selectedTermsId);
            ViewController::sendViewData('post', $post);
            ViewController::sendViewData('title', 'Actualizar entrada');
            ViewController::view('form');
        }
    }
    
    public function delete($id) {
        $isCallAJAX   = Arrays::get($_POST, 'deleteAJAX');
        $messages     = 'Error al borrar la entrada.';
        $typeMessage  = Messages::TYPE_DANGER;
        $postsManager = new PostsManager();
        
        if (!empty($postsManager->delete($id))) {
            $messages    = 'Entrada borrada correctamente.';
            $typeMessage = Messages::TYPE_SUCCESS;
        }
        
        Messages::addMessage($messages, $typeMessage);
        
        if (empty($isCallAJAX)) {
            $this->index();
        }else{
            ViewController::singleViewDirectory('messages');
        }
    }
    
    public function reloadAJAX() {
        $this->read();
        ViewController::singleView('data');
    }
    
}
