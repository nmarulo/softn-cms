<?php

/**
 * Modulo del modelo de etiquetas.
 * Gestiona el proceso de insertar una etiqueta.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\Term;
use SoftnCMS\models\admin\base\ModelInsert;

/**
 * Clase que gestiona el proceso de insertar una etiqueta.
 *
 * @author Nicolás Marulanda P.
 */
class TermInsert extends ModelInsert {

    /** @var string Nombre de las columnas. */
    private static $COLUMNS = Term::TERM_NAME . ', ' . Term::TERM_DESCRIPTION . ', ' . Term::TERM_COUNT;

    /** @var string Nombre de los indices para preparar la consulta. */
    private static $VALUES = ':' . Term::TERM_NAME . ', :' . Term::TERM_DESCRIPTION . ', :' . Term::TERM_COUNT;

    /** @var string Nombre de la etiqueta. */
    private $termName;

    /** @var string Descripción de la etiqueta. */
    private $termDescription;

    public function __construct($termName, $termDescription) {
        parent::__construct(Term::getTableName(), self::$COLUMNS, self::$VALUES);
        $this->termName = $termName;
        $this->termDescription = $termDescription;
    }

    /**
     * Metodo que establece los datos a preparar.
     */
    protected function prepare() {
        $this->addPrepare(':' . Term::TERM_NAME, $this->termName, \PDO::PARAM_STR);
        $this->addPrepare(':' . Term::TERM_DESCRIPTION, $this->termDescription, \PDO::PARAM_STR);
        $this->addPrepare(':' . Term::TERM_COUNT, 0, \PDO::PARAM_INT);
    }

}
