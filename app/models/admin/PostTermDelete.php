<?php

/**
 * Modulo del modelo post-etiqueta.
 * Gestiona el borrado de relaciones post-etiqueta.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\PostTerm;

/**
 * Clase que gestiona el borrado de relaciones post-etiqueta.
 *
 * @author Nicolás Marulanda P.
 */
class PostTermDelete {

    /** @var array Identificador de las etiquetas. */
    private $termsID;

    /** @var int Identificador del post. */
    private $postID;

    /** @var array Lista con los indices, valores y tipos de datos para la consulta. */
    private $prepareStatement;

    /**
     * Constructor.
     * @param array $termsID Identificadores de las etiquetas.
     * @param int $postID Identificador del post.
     */
    public function __construct($termsID, $postID) {
        $this->prepareStatement = [];
        $this->termsID = $termsID;
        $this->postID = $postID;
    }

    /**
     * Metodo que borra los datos.
     * @return bool Si es TRUE, todo se realizo correctamente.
     */
    public function delete() {
        $db = DBController::getConnection();
        $table = PostTerm::getTableName();
        $parameterTermID = PostTerm::RELATIONSHIPS_TERM_ID;
        $parameterPostID = PostTerm::RELATIONSHIPS_POST_ID;
        $where = "$parameterTermID = :$parameterTermID AND $parameterPostID = :$parameterPostID";
        $count = \count($this->termsID);
        $error = \FALSE;

        for ($i = 0; $i < $count && !$error; ++$i) {
            $termID = $this->termsID[$i];
            $this->prepareStatement = [];
            $this->prepare($termID);
            $error = !$db->delete($table, $where, $this->prepareStatement);
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
