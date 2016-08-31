<?php

/**
 * Modulo del controlador de la pagina de configuración.
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\Controller;
use SoftnCMS\controllers\Messages;
use SoftnCMS\models\admin\Options;
use SoftnCMS\models\admin\OptionUpdate;

/**
 * Clase del controlador de la pagina de configuración.
 *
 * @author Nicolás Marulanda P.
 */
class OptionController extends Controller {

    /**
     * Metodo llamado por la funcion index.
     * @return array
     */
    protected function dataIndex() {
        //comprueba si hay datos para actualizar.
        $this->dataUpdate();
        $options = Options::selectAll();
        
        return $options->getAll();
    }

    /**
     * Metodo que actualiza los datos configurables del sitio.
     */
    private function dataUpdate() {
        if (\filter_input(\INPUT_POST, 'update')) {
            $options = Options::selectAll();
            $dataInput = $this->getDataInput();
            /*
             * Usando el indices de $DATAINPUT, se obtiene
             * de $OPTIONS cada instancia OPTION con sus datos,
             * luego en OPTIONUPDATE se comprueba que datos seran actualizados.
             */
            $keys = \array_keys($dataInput);
            $count = \count($keys);
            $error = \FALSE;
            $optionName = '';//En caso de error, contiene el nombre de la opción

            for ($i = 0; $i < $count && !$error; ++$i) {
                $optionName = $keys[$i];
                $optionValue = $dataInput[$optionName];
                $option = $options->getByID($optionName);
                $update = new OptionUpdate($option, $optionValue);
                $error = !$update->update();
            }

            if ($error) {
                Messages::addError("Error al actualizar '$optionName'");
            } else {
                Messages::addSuccess('Actualizado correctamente.');
            }
        }
    }

    /**
     * Metodo que obtiene los datos de los campos INPUT del formulario.
     * @return array
     */
    private function getDataInput() {
        return [
            'optionTitle' => \filter_input(\INPUT_POST, 'optionTitle'),
            'optionDescription' => \filter_input(\INPUT_POST, 'optionDescription'),
            'optionEmailAdmin' => \filter_input(\INPUT_POST, 'optionEmailAdmin'),
            'optionSiteUrl' => \filter_input(\INPUT_POST, 'optionSiteUrl'),
            'optionPaged' => \filter_input(\INPUT_POST, 'optionPaged'),
            'optionTheme' => \filter_input(\INPUT_POST, 'optionTheme'),
            'optionMenu' => \filter_input(\INPUT_POST, 'optionMenu'),
        ];
    }

}
