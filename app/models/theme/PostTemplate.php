<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\models\theme;

use SoftnCMS\models\admin\Post;
use SoftnCMS\models\admin\PostsCategories;
use SoftnCMS\models\admin\Category;
use SoftnCMS\models\admin\Categories;
use SoftnCMS\models\admin\PostsTerms;
use SoftnCMS\models\admin\Term;
use SoftnCMS\models\admin\Terms;

/**
 * Description of PostTemplate
 * @author Nicolás Marulanda P.
 */
class PostTemplate {
    
    /** @var Post Instancia. */
    private $post;
    
    /** @var array Lista de comentarios. */
    private $comments;
    
    /** @var array Lista de categorías. */
    private $categories;
    
    /** @var array Lista de etiquetas. */
    private $terms;
    
    /** @var array Lista de identificadores(ID) de las etiquetas vinculadas. */
    private $postsTerms;
    
    /** @var array Lista de identificadores(ID) de las categorías vinculadas. */
    private $postsCategories;
    
    /**
     * Constuctor.
     *
     * @param Post $post Instancia.
     */
    public function __construct($post, $commentLimit = '') {
        $this->post       = $post;
        $this->comments   = $this->postComments($commentLimit);
        $this->categories = $this->postCategories();
        $this->terms      = $this->postTerms();
    }
    
    private function postComments($commentLimit) {
        $comments = PostCommentsTemplate::selectByPostID($this->getID(), $commentLimit);
        
        if ($comments === \FALSE) {
            
            return [];
        }
        
        $commentsTemplate = new CommentsTemplate();
        $commentsTemplate->addData($comments->getAll());
        
        return $commentsTemplate->getAll();
    }
    
    public function getID() {
        return $this->post->getID();
    }
    
    private function postCategories() {
        $this->postsCategories = PostsCategories::selectByPostID($this->getID());
        
        if ($this->postsCategories === \FALSE) {
            
            return [];
        }
        
        $categories = new Categories();
        
        foreach ($this->postsCategories as $value) {
            $category = Category::selectByID($value);
            $categories->add($category);
        }
        
        $categoriesTemplate = new CategoriesTemplate();
        $categoriesTemplate->addData($categories->getAll());
        
        return $categoriesTemplate->getAll();
    }
    
    private function postTerms() {
        $this->postsTerms = PostsTerms::selectByPostID($this->getID());
        
        if ($this->postsTerms === \FALSE) {
            
            return [];
        }
        
        $terms = new Terms();
        
        foreach ($this->postsTerms as $value) {
            $term = Term::selectByID($value);
            $terms->add($term);
        }
        
        $termsTemplate = new TermsTemplate();
        $termsTemplate->addData($terms->getAll());
        
        return $termsTemplate->getAll();
    }
    
    public function getInstance() {
        return $this->post;
    }
    
    public function getPostUrl($isEcho = \TRUE) {
        global $urlSite;
        
        if (!$isEcho) {
            
            return $urlSite . 'post/' . $this->getID();
        }
        
        echo $urlSite . 'post/' . $this->getID();
    }
    
    public function getPostID($isEcho = \TRUE, $addID = 'post-') {
        if (!$isEcho) {
            
            return $addID . $this->getID();
        }
        
        echo $addID . $this->getID();
    }
    
    public function getPostTitle($isEcho = \TRUE) {
        if (!$isEcho) {
            
            return $this->post->getPostTitle();
        }
        
        echo $this->post->getPostTitle();
    }
    
    public function getPostDate($isEcho = \TRUE) {
        if (!$isEcho) {
            
            return $this->post->getPostDate();
        }
        
        echo $this->post->getPostDate();
    }
    
    public function getPostAuthor($isEcho = \TRUE) {
        if (!$isEcho) {
            
            return $this->post->getUser()
                              ->getUserName();
        }
        
        echo $this->post->getUser()
                        ->getUserName();
    }
    
    public function getPostUrlAuthor($isEcho = \TRUE) {
        global $urlSite;
        
        if (!$isEcho) {
            
            return $urlSite . 'user/' . $this->post->getUserID();
        }
        
        echo $urlSite . 'user/' . $this->post->getUserID();
    }
    
    public function getPostContents($isEcho = \TRUE) {
        if (!$isEcho) {
            
            return $this->post->getPostContents();
        }
        
        echo $this->post->getPostContents();
    }
    
    public function getPostCommentStatus() {
        return $this->post->getCommentStatus();
    }
    
    public function getPostCommentCount() {
        return $this->post->getCommentCount();
    }
    
    public function getPostCategories() {
        return $this->categories;
    }
    
    public function getPostTerms() {
        return $this->terms;
    }
    
    public function getPostComments() {
        return $this->comments;
    }
    
    public function hasPostComments() {
        return $this->post->getCommentCount() > 0;
    }
    
    public function hasPostCategories() {
        return $this->postsCategories !== \FALSE;
    }
    
    public function hasPostTerms() {
        return $this->postsTerms !== \FALSE;
    }
    
}
