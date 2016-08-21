<?php

/**
 * Modulo del modelo de etiquetas.
 * Gestiona grupos de etiquetas.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\Term;

/**
 * Clase que gestiona grupos de etiquetas.
 *
 * @author Nicolás Marulanda P.
 */
class Terms {

    /**
     * Lista, donde el indice o clave corresponde al ID.
     * @var array 
     */
    private $terms;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->terms = [];
    }

    /**
     * Metodo que obtiene todas las etiquetas de la base de datos.
     * @return Terms
     */
    public static function selectAll() {
        return self::select();
    }

    /**
     * Metodo que obtiene los etiquetas segun las especificaciones dadas.
     * @param int|string $value Valor a buscar.
     * @param string $column Nombre de la columna en la tabla.
     * @param int $dataType [Opcional] Por defecto \PDO::PARAM_STR. Tipo de dato.
     * @return Terms
     */
    private static function selectBy($value, $column, $dataType = \PDO::PARAM_STR) {
        $parameter = ":$column";
        $where = "$column = $parameter";
        $prepare[] = DBController::prepareStatement($parameter, $value, $dataType);

        return self::select($where, $prepare);
    }

    /**
     * Metodo que realiza una consulta a la base de datos.
     * @param string $where [Opcional] Condiciones.
     * @param array $prepare [Opcional] Lista de indices a reemplazar en la consulta.
     * @param string $columns [Opcional] Por defecto "*". Columnas.
     * @param int $limit [Opcional] Numero de datos a retornar.
     * @param string $orderBy [Opcional] Por defecto "ID DESC". Ordenar por.
     * @return Terms
     */
    private static function select($where = '', $prepare = [], $columns = '*', $limit = '', $orderBy = 'ID DESC') {
        $db = DBController::getConnection();
        $table = Term::getTableName();
        $fetch = 'fetchAll';
        $select = $db->select($table, $fetch, $where, $prepare, $columns, $orderBy, $limit);
        $terms = new Terms();
        $terms->addTerms($select);

        return $terms;
    }

    /**
     * Metodo que obtiene todas las etiquetas.
     * @return array
     */
    public function getTerms() {
        return $this->terms;
    }

    /**
     * Metodo que obtiene, segun su ID, una etiqueta.
     * @param int $id
     * @return Term
     */
    public function getTerm($id) {
        return $this->terms[$id];
    }

    /**
     * Metodo que agrega una etiqueta a la lista.
     * @param Term $term
     */
    public function addTerm(Term $term) {
        $this->terms[$term->getID()] = $term;
    }

    /**
     * Metodo que obtiene un array con los datos de las etiquetas y los agrega a la lista.
     * @param array $term
     */
    public function addTerms($term) {
        foreach ($term as $value) {
            $this->addTerm(new Term($value));
        }
    }

    /**
     * Metodo que obtiene el número total de etiquetas.
     * @return int
     */
    public function count() {
        $db = DBController::getConnection();
        $table = Term::getTableName();
        $fetch = 'fetchAll';
        $columns = 'COUNT(*) AS count';
        $select = $db->select($table, $fetch, '', [], $columns);

        return $select[0]['count'];
    }

}
