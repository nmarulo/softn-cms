<?php

/**
 * Modulo del modelo post-etiqueta.
 * Gestiona el proceso de insertar relaciones post-etiqueta.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\PostTerm;

/**
 * Clase que gestiona el proceso de insertar relaciones post-etiqueta.
 *
 * @author Nicolás Marulanda P.
 */
class PostTermInsert {
    /** @var string Nombre de las columnas. */
    private static $COLUMNS = PostTerm::RELATIONSHIPS_TERM_ID . ', ' . PostTerm::RELATIONSHIPS_POST_ID;

    /** @var string Nombre de los indices para preparar la consulta. */
    private static $VALUES = ':' . PostTerm::RELATIONSHIPS_TERM_ID . ', ' . ':' . PostTerm::RELATIONSHIPS_POST_ID;

    /** @var array Lista con los indices, valores y tipos de datos para la consulta. */
    private $prepareStatement;

    /** @var array Identificadores de las etiquetas. */
    private $termsID;

    /** @var int Identificador del post. */
    private $postID;

    /**
     * Constructor,
     * @param array $termsID Identificadores de las etiquetas.
     * @param int $postID Identificador del post.
     */
    public function __construct($termsID, $postID) {
        $this->prepareStatement = [];
        $this->termsID = $termsID;
        $this->postID = $postID;
    }
    
    /**
     * Metodo que inserta los datos.
     * @return bool Si es TRUE, todo se realizo correctamente.
     */
    public function insert() {
        $db = DBController::getConnection();
        $table = PostTerm::getTableName();
        $count = \count($this->termsID);
        $error = \FALSE;
        
        for($i = 0; $i < $count && !$error; ++$i){
            $termID = $this->termsID[$i];
            $this->prepareStatement = [];
            $this->prepare($termID);
            $error = !$db->insert($table, self::$COLUMNS, self::$VALUES, $this->prepareStatement);
        }

        return !$error;
    }


    /**
     * Metodo que establece los datos a preparar.
     * @param int $termID Identificador de la etiqueta.
     */
    private function prepare($termID) {
        $this->addPrepare(':' . PostTerm::RELATIONSHIPS_TERM_ID, $termID, \PDO::PARAM_INT);
        $this->addPrepare(':' . PostTerm::RELATIONSHIPS_POST_ID, $this->postID, \PDO::PARAM_INT);
    }

    /**
     * Metodo que guarda los datos establecidos.
     * @param string $parameter Indice a buscar. EJ: ":ID"
     * @param string $value Valor del indice.
     * @param int $dataType Tipo de dato. EJ: \PDO::PARAM_*
     */
    private function addPrepare($parameter, $value, $dataType) {
        $this->prepareStatement[] = DBController::prepareStatement($parameter, $value, $dataType);
    }
}
