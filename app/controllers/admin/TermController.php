<?php

/**
 * Modulo del controlador de la pagina de etiquetas.
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\BaseController;
use SoftnCMS\controllers\Messages;
use SoftnCMS\models\admin\Terms;
use SoftnCMS\models\admin\Term;
use SoftnCMS\models\admin\TermDelete;
use SoftnCMS\models\admin\TermInsert;
use SoftnCMS\models\admin\TermUpdate;

/**
 * Clase del controlador de la pagina de etiquetas.
 *
 * @author Nicolás Marulanda P.
 */
class TermController extends BaseController {

    /**
     * Metodo llamado por la función INDEX.
     * @return array
     */
    protected function dataIndex() {
        $terms = Terms::selectAll();
        $output = [];
        
        if($terms !== \FALSE){
            $output = $terms->getAll();
        }

        return ['terms' => $output];
    }

    /**
     * Metodo llamado por la función INSERT.
     * @return array
     */
    protected function dataInsert() {
        global $urlSite;

        if (filter_input(\INPUT_POST, 'publish')) {
            $dataInput = $this->getDataInput();
            $insert = new TermInsert($dataInput['termName'], $dataInput['termDescription']);

            if ($insert->insert()) {
                Messages::addSuccess('Etiqueta publicada correctamente.');
                //Si todo es correcto se muestra la pagina de edición.
                header("Location: $urlSite" . 'admin/term/update/' . $insert->getLastInsertId());
                exit();
            }
            Messages::addError('Error al publicar la etiqueta.');
        }

        return [
            //Datos por defecto a mostrar en el formulario.
            'term' => Term::defaultInstance(),
            /*
             * Booleano que indica si muestra el encabezado
             * "Publicar nuevo" si es FALSE 
             * o "Actualizar" si es TRUE
             */
            'actionUpdate' => \FALSE
        ];
    }

    /**
     * Metodo llamado por la función UPDATE.
     * @param int $id
     * @return array
     */
    protected function dataUpdate($id) {
        global $urlSite;

        $term = Term::selectByID($id);

        //En caso de que no exista.
        if (empty($term)) {
            Messages::addError('Error. La etiqueta no existe.');
            header("Location: $urlSite" . 'admin/term');
            exit();
        }

        if (filter_input(\INPUT_POST, 'update')) {
            $dataInput = $this->getDataInput();
            $update = new TermUpdate($term, $dataInput['termName'], $dataInput['termDescription']);

            //Si ocurre un error la función "$update->update()" retorna FALSE.
            if ($update->update()) {
                Messages::addSuccess('Etiqueta actualizada correctamente.');
                $term = $update->getDataUpdate();
            } else {
                Messages::addError('Error al actualizar la etiqueta.');
            }
        }

        return [
            //Instancia Term
            'term' => $term,
            /*
             * Booleano que indica si muestra el encabezado
             * "Publicar nuevo" si es FALSE 
             * o "Actualizar" si es TRUE
             */
            'actionUpdate' => \TRUE
        ];
    }

    /**
     * Metodo llamado por la función DELETE.
     * @param int $id
     * @return array
     */
    protected function dataDelete($id) {
        /*
         * Ya que este metodo no tiene modulo vista propio
         * se carga el modulo vista INDEX, asi que se retornan los datos
         * para esta vista.
         */

        $delete = new TermDelete($id);
        $output = $delete->delete();

        if ($output) {
            Messages::addSuccess('Etiqueta borrada correctamente.');
        } elseif ($output === 0) {
            Messages::addWarning('La etiqueta no existe.');
        } else {
            Messages::addError('Error al borrar la etiqueta.');
        }

        return $this->dataIndex();
    }

    /**
     * Metodo que obtiene los datos de los campos INPUT del formulario.
     * @return array
     */
    protected function getDataInput() {
        return [
            'termName' => \filter_input(\INPUT_POST, 'termName'),
            'termDescription' => \filter_input(\INPUT_POST, 'termDescription'),
        ];
    }

}
