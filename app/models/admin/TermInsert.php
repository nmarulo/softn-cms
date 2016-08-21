<?php

/**
 * Modulo del modelo de etiquetas.
 * Gestiona el proceso de insertar una etiqueta.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\Term;

/**
 * Clase que gestiona el proceso de insertar una etiqueta.
 *
 * @author Nicolás Marulanda P.
 */
class TermInsert {

    /** @var string Nombre de las columnas. */
    private static $COLUMNS = Term::TERM_NAME . ', ' . Term::TERM_DESCRIPTION . ', ' . Term::TERM_COUNT;

    /** @var string Nombre de los indices para preparar la consulta. */
    private static $VALUES = ':' . Term::TERM_NAME . ', :' . Term::TERM_DESCRIPTION . ', :' . Term::TERM_COUNT;

    /** @var array Lista con los indices, valores y tipos de datos para la consulta. */
    private $prepareStatement;

    /** @var int Identificador del INSERT realizado. */
    private $lastInsertId;

    /** @var string Nombre de la etiqueta. */
    private $termName;

    /** @var string Descripción de la etiqueta. */
    private $termDescription;

    public function __construct($termName, $termDescription) {
        $this->prepareStatement = [];
        $this->termName = $termName;
        $this->termDescription = $termDescription;
    }

    /**
     * Metodo que realiza el proceso de insertar la etiqueta en la base de datos.
     * @return bool Si es TRUE, todo se realizo correctamente.
     */
    public function insert() {
        $db = DBController::getConnection();
        $table = Term::getTableName();
        $this->prepare();

        if ($db->insert($table, self::$COLUMNS, self::$VALUES, $this->prepareStatement)) {
            $this->lastInsertId = $db->lastInsertId();
            return \TRUE;
        }

        return \FALSE;
    }

    /**
     * Metodo que obtiene el identificador del nuevo dato.
     * @return int
     */
    public function getLastInsertId() {
        return $this->lastInsertId;
    }

    /**
     * Metodo que establece los datos a preparar.
     */
    private function prepare() {
        $this->addPrepare(':' . Term::TERM_NAME, $this->termName, \PDO::PARAM_STR);
        $this->addPrepare(':' . Term::TERM_DESCRIPTION, $this->termDescription, \PDO::PARAM_STR);
        $this->addPrepare(':' . Term::TERM_COUNT, 0, \PDO::PARAM_INT);
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
