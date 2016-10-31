<?php

/**
 * Modulo modelo: Gestiona los datos de un post para la plantilla de la aplicación.
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
 * Clase PostTemplate para gestionar los datos de un post para la plantilla de la aplicación.
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
     * Constructor.
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
    
    /**
     * Método que obtiene las categorías vinculadas al post.
     * @return array
     */
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
    
    /**
     * Método que obtiene las etiquetas vinculadas al post.
     * @return array
     */
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
    
    /**
     * Método que obtiene los comentarios del post.
     * @return array
     */
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
     * Método que obtiene las categorías del post.
     * @return array
     */
    public function getPostCategories() {
        return $this->categories;
    }
    
    /**
     * Método que obtiene las etiquetas del post.
     * @return array
     */
    public function getPostTerms() {
        return $this->terms;
    }
    
    /**
     * Método que obtiene los comentarios del post.
     * @return array
     */
    public function getPostComments() {
        return $this->comments;
    }
    
    /**
     * Método que comprueba si hay comentarios.
     * @return bool
     */
    public function hasPostComments() {
        return !empty($this->comments);
    }
    
    /**
     * Método que comprueba si hay categorías.
     * @return bool
     */
    public function hasPostCategories() {
        return !empty($this->categories);
    }
    
    /**
     * Método que comprueba si hay etiquetas.
     * @return bool
     */
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
