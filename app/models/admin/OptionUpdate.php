<?php

/**
 * Modulo del modelo de las opciones configurables de la aplicación.
 * Gestiona la actualización de las opciones.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\base\ModelUpdate;

/**
 * Clase que gestiona la actualización de las opciones.
 *
 * @author Nicolás Marulanda P.
 */
class OptionUpdate extends ModelUpdate {

    /** @var Option Instancia con los datos sin modificar. */
    private $option;

    /** @var string Valor. */
    private $optionValue;

    /**
     * Constructor.
     * @param Option $option Instancia con los datos sin modificar.
     * @param string $optionValue Valor.
     */
    public function __construct(Option $option, $optionValue) {
        parent::__construct(Option::getTableName());
        $this->option = $option;
        $this->optionValue = $optionValue;
    }

    /**
     * Metodo que obtiene el objeto con los datos actualizados.
     * @return Option
     */
    public function getDataUpdate() {
        //Obtiene el primer dato el cual corresponde al id.
        $id = $this->prepareStatement[0]['value'];

        return Option::selectByID($id);
    }

    /**
     * Metodo que establece los datos a preparar.
     * @return bool Si es TRUE, no hay datos para actualizar.
     */
    protected function prepare() {
        $this->addPrepare(':' . Option::ID, $this->option->getID(), \PDO::PARAM_INT);
        $this->checkFields($this->option->getOptionValue(), $this->optionValue, Option::OPTION_VALUE, \PDO::PARAM_STR);

        return empty($this->dataColumns);
    }

}
