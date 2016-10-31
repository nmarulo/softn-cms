<?php

/**
 * Clase común de modelos.
 */

namespace SoftnCMS\models\admin\base;

use SoftnCMS\controllers\DBController;

/**
 * Clase BaseModels
 * @author Nicolás Marulanda P.
 */
class BaseModels {
    
    /**
     * Método que obtiene un objeto según las especificaciones dadas.
     *
     * @param string     $table    Nombre de la tabla.
     * @param int|string $value    Valor a buscar.
     * @param string     $column   Nombre de la columna en la tabla.
     * @param int        $dataType Tipo de dato.
     *
     * @return array|bool Si es FALSE, no hay datos.
     */
    protected static function selectBy($table, $value, $column, $dataType = \PDO::PARAM_STR) {
        $parameter = ":$column";
        $where     = "$column = $parameter";
        $prepare[] = DBController::prepareStatement($parameter, $value, $dataType);
        
        return self::select($table, $where, $prepare);
    }
    
    /**
     * Método que realiza una consulta a la base de datos.
     *
     * @param string     $table   Nombre de la tabla.
     * @param string     $where   [Opcional] Condiciones.
     * @param array      $prepare [Opcional] Lista de indices a reemplazar en la consulta.
     * @param string     $columns [Opcional] Por defecto "*". Columnas.
     * @param int|string $limit   [Opcional] Número de datos a retornar.
     * @param string     $orderBy [Opcional] Por defecto "ID DESC". Ordenar por.
     *
     * @return array|bool Si es FALSE, no hay datos.
     */
    protected static function select($table, $where = '', $prepare = [], $columns = '*', $limit = '', $orderBy = 'ID DESC') {
        $db     = DBController::getConnection();
        $select = $db->select($table, 'fetchAll', $where, $prepare, $columns, $orderBy, $limit);
        
        if (empty($select)) {
            return \FALSE;
        }
        
        return $select;
    }
    
}
