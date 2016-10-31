<?php

/**
 * Modulo modelo: Gestiona el borrado de relaciones post-etiqueta.
 */
namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\base\ModelDelete;

/**
 * Clase PostTermDelete para gestionar el borrado de relaciones post-etiqueta.
 * @author Nicolás Marulanda P.
 */
class PostTermDelete extends ModelDelete {
    
    /** @var array Identificador de las etiquetas. */
    private $termsID;
    
    /** @var int Identificador del post. */
    private $postID;
    
    /** @var int Identificador de la etiqueta. */
    private $termID;
    
    /**
     * Constructor.
     *
     * @param array $termsID Identificadores de las etiquetas.
     * @param int   $postID  Identificador del post.
     */
    public function __construct($termsID, $postID) {
        parent::__construct(0, PostTerm::getTableName());
        $this->termsID = $termsID;
        $this->postID  = $postID;
        $this->termID  = 0;
    }
    
    /**
     * Método que borra los datos.
     * @return bool Si es TRUE, todo se realizo correctamente.
     */
    public function delete() {
        $db              = DBController::getConnection();
        $table           = PostTerm::getTableName();
        $parameterTermID = PostTerm::RELATIONSHIPS_TERM_ID;
        $parameterPostID = PostTerm::RELATIONSHIPS_POST_ID;
        $where           = "$parameterTermID = :$parameterTermID AND $parameterPostID = :$parameterPostID";
        $count           = \count($this->termsID);
        $error           = \FALSE;
        
        for ($i = 0; $i < $count && !$error; ++$i) {
            $this->termID = $this->termsID[$i];
            $this->prepare();
            $error                  = !$db->delete($table, $where, $this->prepareStatement);
            $this->prepareStatement = [];
        }
        
        return !$error;
    }
    
    /**
     * Método que establece los datos a preparar.
     */
    protected function prepare() {
        $this->addPrepare(':' . PostTerm::RELATIONSHIPS_TERM_ID, $this->termID, \PDO::PARAM_INT);
        $this->addPrepare(':' . PostTerm::RELATIONSHIPS_POST_ID, $this->postID, \PDO::PARAM_INT);
    }
    
}
