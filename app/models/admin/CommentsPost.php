<?php
/**
 * Modulo modelo: Gestiona grupos de comentarios de un post.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\base\BaseModels;

/**
 * Clase CommentsPost para gestionar grupos de comentarios de un post.
 * @author Nicolás Marulanda P.
 */
class CommentsPost extends BaseModels {
    
    /**
     * Lista de identificadores(ID) de comentarios, donde el indice o clave corresponde al ID
     * del post.
     * @var array
     */
    private $commentsID;
    
    /**
     * Constructor.
     */
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
    
    /**
     * Método que obtiene un array con los datos de los comentarios y los agrega a la lista.
     *
     * @param $comments
     */
    public function addData($comments) {
        foreach ($comments as $value) {
            $this->add($value[Comment::ID], $value[Comment::POST_ID]);
        }
    }
    
    /**
     * Método que agrega los datos de los comentarios.
     *
     * @param $id
     * @param $postID
     */
    public function add($id, $postID) {
        $this->commentsID[$postID][] = $id;
    }
    
    /**
     * Método que obtiene todos los identificadores(ID) de los comentarios de un post.
     *
     * @param $id
     *
     * @return mixed
     */
    public function getComments($id) {
        return $this->commentsID[$id];
    }
}
