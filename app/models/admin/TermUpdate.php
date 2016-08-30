<?php

/**
 * Modulo del modelo de etiquetas.
 * Gestiona la actualización de las etiquetas.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\Term;
use SoftnCMS\models\admin\base\ModelUpdate;

/**
 * Clase que gestiona la actualización de las etiquetas.
 *
 * @author Nicolás Marulanda P.
 */
class TermUpdate extends ModelUpdate {

    /** @var Term Instancia con los datos sin modificar. */
    private $term;

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
        parent::__construct(Term::getTableName());
        $this->term = $term;
        $this->termName = $termName;
        $this->termDescription = $termDescription;
    }

    /**
     * Metodo que obtiene el objeto con los datos actualizados.
     * @return Term
     */
    public function getDataUpdate() {
        //Obtiene el primer dato el cual corresponde al id.
        $id = $this->prepareStatement[0]['value'];

        return Term::selectByID($id);
    }

    /**
     * Metodo que establece los datos a preparar.
     * @return bool Si es TRUE, no hay datos para actualizar.
     */
    protected function prepare() {
        $this->addPrepare(':' . Term::ID, $this->term->getID(), \PDO::PARAM_INT);
        $this->checkFields($this->term->getTermName(), $this->termName, Term::TERM_NAME, \PDO::PARAM_STR);
        $this->checkFields($this->term->getTermDescription(), $this->termDescription, Term::TERM_DESCRIPTION, \PDO::PARAM_STR);

        return empty($this->dataColumns);
    }

}
