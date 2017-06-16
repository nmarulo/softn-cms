<?php
/**
 * TermTemplate.php
 */

namespace SoftnCMS\controllers\template;

use SoftnCMS\controllers\Template;
use SoftnCMS\models\managers\PostsManager;
use SoftnCMS\models\managers\TermsManager;
use SoftnCMS\models\tables\Post;
use SoftnCMS\models\tables\Term;

/**
 * Class TermTemplate
 * @author Nicolás Marulanda P.
 */
class TermTemplate extends Template {
    
    /** @var Term */
    private $term;
    
    /** @var array */
    private $posts;
    
    /**
     * CategoryTemplate constructor.
     *
     * @param Term $term
     * @param bool $initRelationShip
     */
    public function __construct(Term $term = NULL, $initRelationShip = FALSE) {
        $this->term  = $term;
        $this->posts = [];
        
        if ($initRelationShip) {
            $this->initRelationship();
        }
    }
    
    public function initRelationship() {
        $this->initPosts();
    }
    
    private function initPosts() {
        $postsManager = new PostsManager();
        $this->posts  = $postsManager->searchByTermId($this->term->getId());
        $this->posts  = array_map(function(Post $post) {
            return new PostTemplate($post);
        }, $this->posts);
    }
    
    public function initCategory($categoryId) {
        $termsManager = new TermsManager();
        $this->term   = $termsManager->searchById($categoryId);
        
        if (empty($this->term)) {
            throw new \Exception("La etiqueta no existe.");
        }
    }
    
    /**
     * @return Term
     */
    public function getTerm() {
        return $this->term;
    }
    
    /**
     * @param Term $term
     */
    public function setTerm($term) {
        $this->term = $term;
    }
    
    /**
     * @return array
     */
    public function getPosts() {
        return $this->posts;
    }
    
}
