<?php

/**
 * Modulo del controlador de la base de datos.
 */

namespace SoftnCMS\controllers;

use SoftnCMS\models\MySql;

/**
 * Clase controlador de la base de datos.
 *
 * @author Nicolás Marulanda P.
 */
class DBController {

    /**
     * Metodo que obtine la instacia de la conexión a la base de datos.
     * @return MySql|null
     */
    public static function getConnection() {
        $connection = null;

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
     * Metodo que obtiene los indices a reemplazar en la consulta.
     * @param string $parameter Indice a buscar. EJ: ":ID"
     * @param string $value Valor del indice.
     * @param int $dataType Tipo de dato. EJ: \PDO::PARAM_*
     * @return array
     */
    public static function prepareStatement($parameter, $value, $dataType) {
        return [
            'parameter' => $parameter,
            'value' => $value,
            'dataType' => $dataType,
        ];
    }

}
