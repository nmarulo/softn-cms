<?php
/**
 * TestController.php
 */

namespace SoftnCMS\controllers\admin;

use Faker\Factory;
use Faker\Generator;
use SoftnCMS\classes\constants\OptionConstants;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\CategoriesManager;
use SoftnCMS\models\managers\CommentsManager;
use SoftnCMS\models\managers\LicensesManager;
use SoftnCMS\models\managers\MenusManager;
use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\models\managers\PagesManager;
use SoftnCMS\models\managers\PostsCategoriesManager;
use SoftnCMS\models\managers\PostsManager;
use SoftnCMS\models\managers\PostsTermsManager;
use SoftnCMS\models\managers\ProfilesLicensesManager;
use SoftnCMS\models\managers\ProfilesManager;
use SoftnCMS\models\managers\SidebarsManager;
use SoftnCMS\models\managers\TermsManager;
use SoftnCMS\models\managers\UsersManager;
use SoftnCMS\models\tables\Category;
use SoftnCMS\models\tables\Comment;
use SoftnCMS\models\tables\License;
use SoftnCMS\models\tables\Menu;
use SoftnCMS\models\tables\Page;
use SoftnCMS\models\tables\Post;
use SoftnCMS\models\tables\PostCategory;
use SoftnCMS\models\tables\PostTerm;
use SoftnCMS\models\tables\Profile;
use SoftnCMS\models\tables\ProfileLicense;
use SoftnCMS\models\tables\Sidebar;
use SoftnCMS\models\tables\Term;
use SoftnCMS\models\tables\User;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\controller\ControllerAbstract;
use SoftnCMS\util\database\ManagerAbstract;
use SoftnCMS\util\database\MySQL;
use SoftnCMS\util\database\TableAbstract;
use SoftnCMS\util\form\builders\InputIntegerBuilder;
use SoftnCMS\util\HTML;
use SoftnCMS\util\Logger;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Util;

/**
 * Class TestController
 * @author Nicolás Marulanda P.
 */
class TestController extends ControllerAbstract {
    
    public function index() {
        //        $this->redirectToAction("",[
        //            'keyTest' => 'valueTest',
        //            'key2'    => 'value2',
        //        ]);
        
        $this->view();
    }
    
    public function faker() {
        Logger::getInstance()
              ->info("Inicio: generado de datos con FAKER");
        //Ejecutar uno a uno
//        $this->createMenus(10);
//        $this->createSidebars(10);
//        $this->createTerms(100);
//        $this->createCategories(100);
//        $this->createLicenses(100);
//        $this->createProfile(100);
//        $this->createUsers(100);
//        $this->createPosts(100);
//        $this->createPages(100);
//        $this->createComments(100);
//        $this->createPostsCategories();
//        $this->createPostsTerms();
//        $this->createProfilesLicenses();
        
        Logger::getInstance()
              ->info("Fin: generado de datos con FAKER");
        
        $this->view('index');
    }
    
    private function createMenus($total, $randomParent = FALSE) {
        $menusManager = new MenusManager($this->getConnectionDB());
        $this->generate($menusManager, $total, function(Generator $faker) use ($randomParent) {
            $parent = MenusManager::MENU_SUB_PARENT;
            $menu   = new Menu();
            $menu->setMenuTitle($faker->words(3, TRUE));
            $menu->setMenuTotalChildren(0);
            
            if ($randomParent) {
                $parent = $this->getRandMenuId();
            }
            
            $menu->setMenuSub($parent);
            
            return $menu;
        }, "Error al generar los menus (padre).", "Menus (padre) creados correctamente.");
    }
    
    /**
     * @param ManagerAbstract $managerAbstract
     * @param int             $total
     * @param \Closure        $entity
     * @param string          $messageError
     * @param string          $messageSuccess
     *
     * @return bool
     */
    private function generate(ManagerAbstract $managerAbstract, $total, $entity, $messageError, $messageSuccess) {
        $faker    = Factory::create();
        $notError = TRUE;
        
        for ($i = 0; $i < $total && $notError; ++$i) {
            $object   = call_user_func($entity, $faker);
            $notError = $this->create($managerAbstract, $object);
        }
        
        if ($notError) {
            Messages::addSuccess($messageSuccess);
            
            return TRUE;
        }
        
        Messages::addDanger($messageError);
        
        return FALSE;
    }
    
    private function create(ManagerAbstract $managerAbstract, $object) {
        return $managerAbstract->create($object) !== FALSE;
    }
    
    private function getRandMenuId() {
        return $this->getRandId(MenusManager::TABLE);
    }
    
    private function getRandId($table) {
        $query  = 'SELECT %1$s FROM %2$s%3$s ORDER BY rand() LIMIT 1';
        $query  = sprintf($query, ManagerAbstract::COLUMN_ID, DB_PREFIX, $table);
        $result = $this->getConnectionDB()
                       ->select($query);
        
        if (empty($result)) {
            return 1;
        }
        
        return Arrays::findFirst($result);
    }
    
    private function createSidebars($total) {
        $sidebarsManager = new SidebarsManager($this->getConnectionDB());
        $this->generate($sidebarsManager, $total, function(Generator $faker) {
            $sidebar = new Sidebar();
            $sidebar->setSidebarTitle($faker->text(60));
            $sidebar->setSidebarContents($faker->realText($faker->numberBetween(10, 1000)));
            $sidebar->setSidebarPosition($this->getSidebarLastPosition());
            
            return $sidebar;
        }, "Error al crear los sidebars.", "Sidebars creados correctamente.");
    }
    
    private function getSidebarLastPosition() {
        $query  = 'SELECT %1$s FROM %2$s%3$s ORDER BY %4$s LIMIT 1';
        $query  = sprintf($query, SidebarsManager::SIDEBAR_POSITION, DB_PREFIX, SidebarsManager::TABLE, ManagerAbstract::COLUMN_ID);
        $result = $this->getConnectionDB()
                       ->select($query);
        
        if (empty($result)) {
            return 1;
        }
        
        return Arrays::findFirst($result);
    }
    
    private function createTerms($total) {
        $termsManager = new TermsManager($this->getConnectionDB());
        $this->generate($termsManager, $total, function(Generator $faker) {
            $term = new Term();
            $term->setTermName($faker->words(3, TRUE));
            $term->setTermDescription($faker->text);
            $term->setTermPostCount(0);
            
            return $term;
        }, "Error al crear las etiquetas.", "Etiquetas creadas correctamente.");
    }
    
    private function createCategories($total) {
        $categoriesManager = new CategoriesManager($this->getConnectionDB());
        $this->generate($categoriesManager, $total, function(Generator $faker) {
            $category = new Category();
            $category->setCategoryName($faker->words(3, TRUE));
            $category->setCategoryDescription($faker->text);
            $category->setCategoryPostCount(0);
            
            return $category;
        }, "Error al crear las categorías.", "Categorías creadas correctamente.");
    }
    
    private function createLicenses($total) {
        $licensesManager = new LicensesManager($this->getConnectionDB());
        $this->generate($licensesManager, $total, function(Generator $faker) {
            $license = new License();
            $license->setLicenseName($faker->words(3, TRUE));
            $license->setLicenseDescription($faker->text);
            
            return $license;
        }, "Error al crear los permisos.", "Permisos creados correctamente.");
    }
    
    private function createProfile($total) {
        $profilesManager = new ProfilesManager($this->getConnectionDB());
        
        $this->generate($profilesManager, $total, function(Generator $faker) {
            $profile = new Profile();
            $profile->setProfileName($faker->words(3, TRUE));
            $profile->setProfileDescription($faker->text);
            
            return $profile;
        }, "Error al crear los perfiles.", "Perfiles creados correctamente.");
        
    }
    
    private function createUsers($total) {
        $usersManager = new UsersManager($this->getConnectionDB());
        $gravatar     = $usersManager->getGravatar(NULL);
        $this->generate($usersManager, $total, function(Generator $faker) use ($gravatar) {
            $user = new User();
            $user->setUserName($faker->name);
            $user->setUserLogin($faker->userName);
            $user->setUserUrl($faker->url);
            $user->setUserEmail($faker->email);
            $user->setUserPassword(Util::encrypt("password", LOGGED_KEY));
            $user->setUserPostCount(0);
            $user->setUserRegistered($this->getDateTime($faker));
            $user->setUserUrlImage($gravatar->get());
            $user->setProfileId($this->getRandProfileId());
            
            return $user;
        }, "Error al crear los usuarios.", "Usuarios creados correctamente.");
        
    }
    
    private function getDateTime(Generator $faker) {
        return $faker->dateTimeBetween('-10 years')
                     ->format('Y-m-d H:i:s');
    }
    
    private function getRandProfileId() {
        return $this->getRandId(ProfilesManager::TABLE);
    }
    
    private function createPosts($total) {
        $postsManager = new PostsManager($this->getConnectionDB());
        $this->generate($postsManager, $total, function(Generator $faker) {
            $post = new Post();
            $post->setPostTitle($faker->text(45));
            $post->setPostContents($faker->realText($faker->numberBetween(500, 5000)));
            $post->setPostCommentCount(0);
            $post->setPostCommentStatus($faker->numberBetween(0, 1));
            $post->setPostDate($this->getDateTime($faker));
            $post->setPostStatus($faker->numberBetween(0, 1));
            $post->setPostUpdate($this->getDateTime($faker));
            $post->setUserId($this->getRandUserId());
            
            return $post;
        }, "Error al crear las publicaciones.", "Publicaciones creadas correctamente.");
    }
    
    private function getRandUserId() {
        return $this->getRandId(UsersManager::TABLE);
    }
    
    private function createPages($total) {
        $pagesManager = new PagesManager($this->getConnectionDB());
        $this->generate($pagesManager, $total, function(Generator $faker) {
            $page = new Page();
            $page->setPageTitle($faker->text(45));
            $page->setPageDate($this->getDateTime($faker));
            $page->setPageStatus($faker->numberBetween(0, 1));
            $page->setPageContents($faker->realText($faker->numberBetween(500, 5000)));
            $page->setPageCommentStatus($faker->numberBetween(0, 1));
            $page->setPageCommentCount(0);
            
            return $page;
        }, "Error al crear las paginas.", "Paginas creadas correctamente.");
    }
    
    private function createComments($total) {
        $commentsManager = new CommentsManager($this->getConnectionDB());
        $usersManager    = new UsersManager($this->getConnectionDB());
        $this->generate($commentsManager, $total, function(Generator $faker) use ($usersManager) {
            $comment = new Comment();
            $user    = $usersManager->searchById($this->getRandUserId());
            $comment->setCommentUserId($user->getId());
            $comment->setCommentAuthor($user->getUserName());
            $comment->setCommentAuthorEmail($user->getUserEmail());
            $comment->setCommentContents($faker->realText($faker->numberBetween(10, 500)));
            $comment->setCommentDate($this->getDateTime($faker));
            $comment->setCommentStatus($faker->numberBetween(0, 1));
            $comment->setPostId($this->getRandPostId());
            
            return $comment;
        }, "Error al crear los comentarios.", "comentarios creados correctamente.");
    }
    
    private function getRandPostId() {
        return $this->getRandId(PostsManager::TABLE);
    }
    
    private function createPostsCategories() {
        $postsCategoriesManager = new PostsCategoriesManager($this->getConnectionDB());
        $postsManager           = new PostsManager($this->getConnectionDB());
        $categoriesManager      = new CategoriesManager($this->getConnectionDB());
        $categories             = $categoriesManager->searchAll();
        $posts                  = $postsManager->searchAll();
        
        $this->relationship($postsCategoriesManager, function(Post $post, Category $category) {
            $postCategory = new PostCategory();
            $postCategory->setPostId($post->getId());
            $postCategory->setCategoryId($category->getId());
            
            return $postCategory;
        }, $posts, $categories, 'Error al crear los post-category', 'Post-Category creados correctamente.');
    }
    
    private function relationship(ManagerAbstract $managerAbstract, $closure, $objectsOne, $objectsTwo, $messageError, $messageSuccess) {
        $this->relationshipGenerate($managerAbstract, function() use ($closure, $objectsOne, $objectsTwo) {
            $relationshipObjects = [];
            
            array_walk($objectsOne, function($objectOne) use ($closure, &$relationshipObjects, $objectsTwo) {
                $relationshipMax = rand(0, count($objectsTwo));
                
                for ($i = 0; $i < $relationshipMax; ++$i) {
                    //Obtengo una objeto de una posición aleatoria de la lista, la guardo y lo elimino de la lista.
                    $objectRand            = $this->getObjectRandFromArray($objectsTwo);
                    $relationshipObjects[] = call_user_func_array($closure, [
                        $objectOne,
                        $objectRand,
                    ]);
                }
            });
            
            return $relationshipObjects;
        }, $messageError, $messageSuccess);
    }
    
    private function relationshipGenerate(ManagerAbstract $managerAbstract, $closure, $messageError, $messageSuccess) {
        $objects  = call_user_func($closure);
        $total    = count($objects);
        $notError = TRUE;
        
        for ($i = 0; $i < $total && $notError; ++$i) {
            $notError = $this->create($managerAbstract, Arrays::get($objects, $i));
        }
        
        if ($notError) {
            Messages::addSuccess($messageSuccess);
            
            return TRUE;
        }
        
        Messages::addDanger($messageError);
        
        return FALSE;
    }
    
    /**
     * @param $array
     *
     * @return TableAbstract
     */
    private function getObjectRandFromArray(&$array) {
        $pos    = rand(0, count($array) - 1);
        $object = $array[$pos];
        unset($array[$pos]);
        $array = array_merge($array);
        
        return $object;
    }
    
    private function createPostsTerms() {
        $postsTermsManager = new PostsTermsManager($this->getConnectionDB());
        $postsManager      = new PostsManager($this->getConnectionDB());
        $termsManager      = new TermsManager($this->getConnectionDB());
        $terms             = $termsManager->searchAll();
        $posts             = $postsManager->searchAll();
        
        $this->relationship($postsTermsManager, function(Post $post, Term $term) {
            $postTerm = new PostTerm();
            $postTerm->setPostId($post->getId());
            $postTerm->setTermId($term->getId());
            
            return $postTerm;
        }, $posts, $terms, 'Error al crear los post-term', 'Post-Term creados correctamente.');
    }
    
    private function createProfilesLicenses() {
        $profilesLicensesManager = new ProfilesLicensesManager($this->getConnectionDB());
        $profilesManager         = new ProfilesManager($this->getConnectionDB());
        $licensesManager         = new LicensesManager($this->getConnectionDB());
        $profiles                = $profilesManager->searchAll();
        $licenses                = $licensesManager->searchAll();
        
        $this->relationship($profilesLicensesManager, function(Profile $profile, License $license) {
            $profileLicense = new ProfileLicense();
            $profileLicense->setProfileId($profile->getId());
            $profileLicense->setLicenseId($license->getId());
            
            return $profileLicense;
        }, $profiles, $licenses, 'Error al crear los profile-license', 'Profile-license creados correctamente.');
        
    }
    
    protected function formToObject() {
        $test = new User();
        $test->setId($this->getInput('test'));
        
        return ['test' => $test];
    }
    
    protected function formInputsBuilders() {
        return [
            InputIntegerBuilder::init('test')
                               ->build(),
        ];
    }
    
    
    
    
    //    protected function index() {
    //        $mysql = new MySQL();
    //        $query = "SELECT * FROM sn_categories";
    //        var_dump($mysql->select($query));
    //
    //
    //        ob_start();
    //        HTML::buttonSubmit('Test controller button');
    //        $test = ob_get_clean();
    //        //        HTML::initOB()
    //        //            ->method(function() {
    //        //                HTML::buttonSubmit('Test controller button');
    //        //            })
    //        //            ->get();
    //        ViewController::sendViewData('button', $test);
    //    }
    
}
