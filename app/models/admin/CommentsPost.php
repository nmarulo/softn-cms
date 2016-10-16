<?php
/**
 * CommentsPost.php
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\base\BaseModels;

/**
 * Class CommentsPost
 * @author Nicolás Marulanda P.
 */
class CommentsPost extends BaseModels {
    
    private $commentsID;
    
    public function __construct() {
        $this->commentsID = [];
    }
    
    /**
     * Método que obtiene todos los identificadores(ID) de los comentarios vinculadas a un post.
     *
     * @param int $value Identificador del post.
     *
     * @return array|bool Si es False, no hay datos.
     */
    public static function selectByPostID($value) {
        
        $select = self::selectBy(Comment::getTableName(), $value, Comment::POST_ID, \PDO::PARAM_INT);
        
        $output = self::getInstanceData($select);
        
        if ($output === \FALSE) {
            return \FALSE;
        }
        
        return $output->getComments($value);
    }
    
    /**
     * Método que recibe un lista de datos y retorna un instancia.
     *
     * @param array $data Lista de datos.
     *
     * @return CommentsPost|bool Si es FALSE, no hay datos.
     */
    public static function getInstanceData($data) {
        if ($data === \FALSE) {
            return \FALSE;
        }
        
        $output = new CommentsPost();
        $output->addData($data);
        
        return $output;
    }
    
    public function addData($comments) {
        foreach ($comments as $value) {
            $this->add($value[Comment::ID], $value[Comment::POST_ID]);
        }
    }
    
    public function add($id, $postID) {
        $this->commentsID[$postID][] = $id;
    }
    
    public function getComments($id) {
        return $this->commentsID[$id];
    }
}
