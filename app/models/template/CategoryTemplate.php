<?php
/**
 * CategoryTemplate.php
 */

namespace SoftnCMS\models\template;

use SoftnCMS\models\managers\CategoriesManager;
use SoftnCMS\models\managers\PostsManager;
use SoftnCMS\models\tables\Category;
use SoftnCMS\models\tables\Post;
use SoftnCMS\models\TemplateAbstract;
use SoftnCMS\util\database\DBInterface;
use SoftnCMS\util\Logger;

/**
 * Class CategoryTemplate
 * @author Nicolás Marulanda P.
 */
class CategoryTemplate extends TemplateAbstract {
    
    /** @var Category */
    private $category;
    
    /** @var array */
    private $posts;
    
    /**
     * CategoryTemplate constructor.
     *
     * @param Category    $category
     * @param bool        $initRelationShip
     * @param string      $siteUrl
     * @param DBInterface $connectionDB
     */
    public function __construct(Category $category = NULL, $initRelationShip = FALSE, $siteUrl = '', DBInterface $connectionDB = NULL) {
        parent::__construct($siteUrl, $connectionDB);
        $this->category = $category;
        $this->posts    = [];
        
        if ($initRelationShip) {
            $this->initRelationship();
        }
    }
    
    public function initRelationship() {
        $this->initPosts();
    }
    
    public function initPosts() {
        $postsManager = new PostsManager($this->getConnectionDB());
        $this->posts  = $postsManager->searchAllByCategoryId($this->category->getId());
        $this->posts  = array_map(function(Post $post) {
            return new PostTemplate($post, FALSE, $this->getSiteUrl(), $this->getConnectionDB());
        }, $this->posts);
    }
    
    public function initCategory($categoryId) {
        $categoriesManager = new CategoriesManager($this->getConnectionDB());
        $this->category    = $categoriesManager->searchById($categoryId);
        
        if (empty($this->category)) {
            Logger::getInstance()
                  ->error('La categoría no existe.', ['currentCategoryId' => $categoryId]);
            throw new \Exception("La categoría no existe.");
        }
    }
    
    /**
     * @return Category
     */
    public function getCategory() {
        return $this->category;
    }
    
    /**
     * @param Category $category
     */
    public function setCategory($category) {
        $this->category = $category;
    }
    
    /**
     * @return array
     */
    public function getPosts() {
        return $this->posts;
    }
    
}
