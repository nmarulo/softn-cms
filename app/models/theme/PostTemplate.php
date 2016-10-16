<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\models\theme;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\Comment;
use SoftnCMS\models\admin\Comments;
use SoftnCMS\models\admin\CommentsPost;
use SoftnCMS\models\admin\Post;
use SoftnCMS\models\admin\PostsCategories;
use SoftnCMS\models\admin\Categories;
use SoftnCMS\models\admin\PostsTerms;
use SoftnCMS\models\admin\Terms;

/**
 * Description of PostTemplate
 * @author Nicolás Marulanda P.
 */
class PostTemplate extends Post {
    
    /** @var array Lista de categorías. */
    private $categories;
    
    /** @var array Lista de comentarios. */
    private $comments;
    
    /** @var array Lista de etiquetas. */
    private $terms;
    
    /**
     * PostTemplate constructor.
     *
     * @param int $id
     */
    public function __construct($id) {
        $select = self::selectBy(self::getTableName(), $id, self::ID, \PDO::PARAM_INT);
        parent::__construct($select[0]);
        $this->categories = $this->postCategories();
        $this->terms      = $this->postTerms();
        $this->comments   = $this->postComments();
    }
    
    private function postCategories() {
        $postsCategories = PostsCategories::selectByPostID($this->getID());
        
        if ($postsCategories === \FALSE) {
            
            return [];
        }
        
        $categories = new Categories();
        
        foreach ($postsCategories as $id) {
            $category = new CategoryTemplate($id);
            $categories->add($category);
        }
        
        return $categories->getAll();
    }
    
    private function postTerms() {
        $postsTerms = PostsTerms::selectByPostID($this->getID());
        
        if ($postsTerms === \FALSE) {
            
            return [];
        }
        
        $terms = new Terms();
        
        foreach ($postsTerms as $id) {
            $term = new TermTemplate($id);
            $terms->add($term);
        }
        
        return $terms->getAll();
    }
    
    private function postComments() {
        $column       = Comment::POST_ID;
        $parameter    = ":$column";
        $where        = "$column = $parameter AND " . Comment::COMMENT_STATUS . ' = 1';
        $prepare[]    = DBController::prepareStatement($parameter, $this->getID(), \PDO::PARAM_INT);
        $select       = CommentsPost::select(Comment::getTableName(), $where, $prepare);
        $postComments = CommentsPost::getInstanceData($select);
    
        if ($postComments === \FALSE) {
        
            return [];
        }
        
        $postComments = $postComments->getComments($this->getID());
        
        $comments = new Comments();
        foreach ($postComments as $id) {
            $comment = new CommentTemplate($id);
            $comments->add($comment);
        }
        
        return $comments->getAll();
    }
    
    /**
     * @return array
     */
    public function getPostCategories() {
        return $this->categories;
    }
    
    /**
     * @return array
     */
    public function getPostTerms() {
        return $this->terms;
    }
    
    public function getPostComments() {
        return $this->comments;
    }
    
    public function hasPostComments() {
        return !empty($this->comments);
    }
    
    public function hasPostCategories() {
        return !empty($this->categories);
    }
    
    public function hasPostTerms() {
        return !empty($this->terms);
    }
    
    /**
     * Método que incluye la pagina de comentarios en la plantilla.
     */
    public function getComments() {
        $post             = $this;
        $data['template'] = Template::class;
        
        return require THEMES . Template::getNameTheme() . DIRECTORY_SEPARATOR . 'comments.php';
    }
    
}
