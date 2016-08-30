<?php

/**
 * Modulo del modelo de etiquetas.
 * Gestiona grupos de etiquetas.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\Term;
use SoftnCMS\models\admin\base\Models;

/**
 * Clase que gestiona grupos de etiquetas.
 *
 * @author Nicolás Marulanda P.
 */
class Terms extends Models {

    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct(Term::getTableName(), __CLASS__);
    }

    /**
     * Metodo que obtiene todas las etiquetas de la base de datos.
     * @return Terms
     */
    public static function selectAll() {
        $select = self::select(Term::getTableName());

        return self::getInstanceData($select);
    }

    /**
     * Metodo que recibe un lista de datos y retorna un instancia.
     * @param array $data Lista de datos.
     * @return Terms|bool Si es FALSE, no hay datos.
     */
    public static function getInstanceData($data) {
        return parent::getInstance($data, __CLASS__);
    }

    /**
     * Metodo que recibe una lista de datos y los agrega a la lista actual.
     * @param array $data Lista de datos.
     */
    public function addData($data) {
        foreach ($data as $value) {
            $this->add(new Term($value));
        }
    }

}
