<?php

/**
 * Modulo controlador: Obtiene la conexión de la base de datos.
 */

namespace SoftnCMS\controllers;

use SoftnCMS\models\MySql;

/**
 * Clase DBController para obtiene la conexión de la base de datos.
 * @author Nicolás Marulanda P.
 */
class DBController {
    
    /**
     * Método que obtiene la instancia de la conexión a la base de datos.
     * @return MySql|null
     */
    public static function getConnection() {
        $connection = NULL;
        
        switch (\DB_TYPE) {
            case 'mysql':
                $connection = new MySql();
                break;
            default :
                die('Tipo de conexión no disponible.');
                break;
        }
        
        return $connection;
    }
    
    /**
     * Método que obtiene los indices a reemplazar en la consulta.
     * EJ: [
     *   [
     *      'parameter' => ':id',
     *      'value'     => 1,
     *      'dataType'  => PDO::PARAM_INT,
     *   ],
     *   [
     *      'parameter' => ':nombre',
     *      'value'     => 'nicolas',
     *      'dataType'  => PDO::PARAM_STR,
     *   ],
     * ]
     *
     * @param string $parameter Indice a buscar. EJ: ":ID"
     * @param string $value     Valor del indice.
     * @param int    $dataType  Tipo de dato. EJ: \PDO::PARAM_*
     *
     * @return array
     */
    public static function prepareStatement($parameter, $value, $dataType) {
        return [
            'parameter' => $parameter,
            'value'     => $value,
            'dataType'  => $dataType,
        ];
    }
    
}
