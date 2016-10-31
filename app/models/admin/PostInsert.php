<?php

/**
 * Modulo modelo: Gestiona el proceso de insertar posts.
 */
namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\base\ModelInsert;

/**
 * Clase PostInsert para gestionar el proceso de insertar posts.
 * @author Nicolás Marulanda P.
 */
class PostInsert extends ModelInsert {
    
    /** @var string Nombre de las columnas. */
    private static $COLUMNS = Post::POST_TITLE . ', ' . Post::POST_STATUS . ', ' . Post::POST_DATE . ', ' . Post::POST_UPDATE . ', ' . Post::POST_CONTENTS . ', ' . Post::COMMENT_STATUS . ', ' . Post::USER_ID;
    
    /** @var string Nombre de los indices para preparar la consulta. */
    private static $VALUES = ':' . Post::POST_TITLE . ', ' . ':' . Post::POST_STATUS . ', ' . ':' . Post::POST_DATE . ', ' . ':' . Post::POST_UPDATE . ', ' . ':' . Post::POST_CONTENTS . ', ' . ':' . Post::COMMENT_STATUS . ', ' . ':' . Post::USER_ID;
    
    /** @var string Titulo. */
    private $postTitle;
    
    /** @var int Estado. */
    private $postStatus;
    
    /** @var string Contenido. */
    private $postContents;
    
    /** @var int Estado de los comentarios. */
    private $commentStatus;
    
    /** @var int Identificador del autor. */
    private $userID;
    
    /**
     * Constructor.
     *
     * @param string $postTitle     Titulo.
     * @param string $postContents  Contenido.
     * @param int    $commentStatus Estado de los comentarios.
     * @param int    $postStatus    Estado.
     * @param int    $userID        Identificador del autor.
     */
    public function __construct($postTitle, $postContents, $commentStatus, $postStatus, $userID) {
        parent::__construct(Post::getTableName(), self::$COLUMNS, self::$VALUES);
        $this->postTitle     = $postTitle;
        $this->postContents  = $postContents;
        $this->commentStatus = $commentStatus;
        $this->postStatus    = $postStatus;
        $this->userID        = $userID;
    }
    
    /**
     * Método que establece los datos a preparar.
     */
    protected function prepare() {
        $date = \date('Y-m-d H:i:s', \time());
        $this->addPrepare(':' . Post::POST_TITLE, $this->postTitle, \PDO::PARAM_STR);
        $this->addPrepare(':' . Post::POST_STATUS, $this->postStatus, \PDO::PARAM_INT);
        $this->addPrepare(':' . Post::POST_DATE, $date, \PDO::PARAM_STR);
        $this->addPrepare(':' . Post::POST_UPDATE, $date, \PDO::PARAM_STR);
        $this->addPrepare(':' . Post::POST_CONTENTS, $this->postContents, \PDO::PARAM_STR);
        $this->addPrepare(':' . Post::COMMENT_STATUS, $this->commentStatus, \PDO::PARAM_INT);
        $this->addPrepare(':' . Post::USER_ID, $this->userID, \PDO::PARAM_INT);
    }
    
}
