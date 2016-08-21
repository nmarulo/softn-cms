<?php

/**
 * Modulo del modelo de etiquetas.
 * Gestiona la actualización de las etiquetas.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\Term;

/**
 * Clase que gestiona la actualización de las etiquetas.
 *
 * @author Nicolás Marulanda P.
 */
class TermUpdate {

    /** @var Term Instancia con los datos sin modificar. */
    private $term;

    /** @var string Campos que seran actualizados. */
    private $dataColumns;

    /** @var array Lista con los indices, valores y tipos de datos para la consulta. */
    private $prepareStatement;

    /** @var string Nombre de la etiqueta. */
    private $termName;

    /** @var string Descripción de la etiqueta. */
    private $termDescription;

    /**
     * Constructor.
     * @param Term $term Instancia con los datos sin modificar.
     * @param string $termName Nombre de la etiqueta.
     * @param string $termDescription Descripción de la etiqueta.
     */
    public function __construct($term, $termName, $termDescription) {
        $this->term = $term;
        $this->prepareStatement = [];
        $this->termName = $termName;
        $this->termDescription = $termDescription;
    }

    /**
     * Metodo que actualiza los datos en la base de datos.
     * @return bool Si es TRUE, todo se realizo correctamente.
     */
    public function update() {
        $db = DBController::getConnection();
        $table = Term::getTableName();
        $parameter = ':id';
        $where = "ID = $parameter";
        $newData = $this->term->getID();
        $dataType = \PDO::PARAM_INT;
        $this->addPrepare($parameter, $newData, $dataType);

        /*
         * Si no hay datos, no se ejecuta la consulta. 
         * Se retorna TRUE para evitar un error.
         */
        if ($this->prepare()) {
            return \TRUE;
        }

        return $db->update($table, $this->dataColumns, $where, $this->prepareStatement);
    }

    /**
     * Metodo que obtiene la etiqueta con los datos actualizados.
     * @return Term
     */
    public function getTerm() {
        $db = DBController::getConnection();
        $columns = '*';
        $where = 'ID = :id';
        $fetch = 'fetchAll';
        $table = Term::getTableName();
        //Obtiene el primer dato el cual corresponde al id.
        $prepare = [$this->prepareStatement[0]];
        $select = $db->select($table, $fetch, $where, $prepare, $columns);
        $term = new Term($select[0]);

        return $term;
    }

    /**
     * Metodo que establece los datos a preparar.
     * @return bool Si es TRUE, no hay datos para actualizar.
     */
    private function prepare() {
        $this->checkFields($this->term->getTermName(), $this->termName, Term::TERM_NAME, \PDO::PARAM_STR);
        $this->checkFields($this->term->getTermDescription(), $this->termDescription, Term::TERM_DESCRIPTION, \PDO::PARAM_STR);

        return empty($this->dataColumns);
    }

    /**
     * Metodo que comprueba si el nuevo dato es diferente al de la base de datos, 
     * de ser asi el campo sera actualizado.
     * @param string|int $oldData Dato actual.
     * @param string|int $newData Dato nuevo.
     * @param string $column Nombre de la columna en la tabla.
     * @param int $dataType Tipo de dato.
     */
    private function checkFields($oldData, $newData, $column, $dataType) {
        if ($oldData != $newData) {
            $parameter = ':' . $column;
            $this->addSetDataSQL($column, $parameter);
            $this->addPrepare($parameter, $newData, $dataType);
        }
    }

    /**
     * Metodo que agrega los datos que seran actualizados.
     * @param string $column Nombre de la columna en la tabla.
     * @param string $data Nuevo valor.
     */
    private function addSetDataSQL($column, $data) {
        $this->dataColumns .= empty($this->dataColumns) ? '' : ', ';
        $this->dataColumns .= "$column = $data";
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
