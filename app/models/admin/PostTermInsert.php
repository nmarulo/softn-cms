<?php

/**
 * Modulo modelo: Gestiona el proceso de insertar relaciones post-etiqueta.
 */
namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\base\ModelInsert;

/**
 * Clase PostTermInsert para gestionar el proceso de insertar relaciones post-etiqueta.
 * @author Nicolás Marulanda P.
 */
class PostTermInsert extends ModelInsert {
    
    /** @var string Nombre de las columnas. */
    private static $COLUMNS = PostTerm::RELATIONSHIPS_TERM_ID . ', ' . PostTerm::RELATIONSHIPS_POST_ID;
    
    /** @var string Nombre de los indices para preparar la consulta. */
    private static $VALUES = ':' . PostTerm::RELATIONSHIPS_TERM_ID . ', ' . ':' . PostTerm::RELATIONSHIPS_POST_ID;
    
    /** @var array Identificadores de las etiquetas. */
    private $termsID;
    
    /** @var int Identificador del post. */
    private $postID;
    
    /** @var int Identificador de la etiqueta. */
    private $termID;
    
    /**
     * Constructor,
     *
     * @param array $termsID Identificadores de las etiquetas.
     * @param int   $postID  Identificador del post.
     */
    public function __construct($termsID, $postID) {
        parent::__construct(PostTerm::getTableName(), self::$COLUMNS, self::$VALUES);
        $this->termsID = $termsID;
        $this->postID  = $postID;
        $this->termID  = 0;
    }
    
    /**
     * Método que inserta los datos.
     * @return bool Si es TRUE, todo se realizo correctamente.
     */
    public function insert() {
        $db    = DBController::getConnection();
        $table = PostTerm::getTableName();
        $count = \count($this->termsID);
        $error = \FALSE;
        
        for ($i = 0; $i < $count && !$error; ++$i) {
            $this->termID = $this->termsID[$i];
            $this->prepare();
            $error                  = !$db->insert($table, self::$COLUMNS, self::$VALUES, $this->prepareStatement);
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
