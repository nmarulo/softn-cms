<?php

/**
 * Modulo controlador: Pagina de etiquetas del panel de administración.
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\BaseController;
use SoftnCMS\controllers\Form;
use SoftnCMS\controllers\Messages;
use SoftnCMS\controllers\Pagination;
use SoftnCMS\controllers\Token;
use SoftnCMS\helpers\ArrayHelp;
use SoftnCMS\helpers\form\builders\InputAlphanumericBuilder;
use SoftnCMS\helpers\Helps;
use SoftnCMS\models\admin\template\Template;
use SoftnCMS\models\admin\Terms;
use SoftnCMS\models\admin\Term;
use SoftnCMS\models\admin\TermDelete;
use SoftnCMS\models\admin\TermInsert;
use SoftnCMS\models\admin\TermUpdate;

/**
 * Clase TermController de la pagina de etiquetas del panel de administración.
 * @author Nicolás Marulanda P.
 */
class TermController extends BaseController {
    
    /**
     * Método llamado por la función INDEX.
     *
     * @param array $data Lista de argumentos.
     *
     * @return array
     */
    protected function dataIndex($data) {
        $output     = [];
        $countData  = Terms::count();
        $pagination = new Pagination(ArrayHelp::get($data, 'paged'), $countData);
        $limit      = $pagination->getBeginRow() . ',' . $pagination->getRowCount();
        $terms      = Terms::selectByLimit($limit);
        Template::setPagination($pagination);
        
        if ($terms !== \FALSE) {
            $output = $terms->getAll();
        }
        
        return [
            'terms' => $output,
        ];
    }
    
    /**
     * Método llamado por la función INSERT.
     * @return array
     */
    protected function dataInsert() {
        if (Form::submit('publish')) {
            $dataInput = $this->getDataInput();
            
            if ($dataInput !== FALSE) {
                //Las etiquetas tienen nombres únicos, si ya existe se le agrega un numero al final
                $termName = $this->checkName($dataInput['termName']);
                $insert   = new TermInsert($termName, $dataInput['termDescription']);
                
                if ($insert->insert()) {
                    Messages::addSuccess('Etiqueta publicada correctamente.');
                    //Si es correcto se muestra la pagina de edición.
                    Helps::redirectRoute('update/' . $insert->getLastInsertId());
                }
            }
            Messages::addError('Error al publicar la etiqueta.');
        }
        
        return [
            //Datos por defecto a mostrar en el formulario.
            'term' => Term::defaultInstance(),
        ];
    }
    
    /**
     * Método que obtiene los datos de los campos INPUT del formulario.
     * @return array|bool
     */
    protected function getDataInput() {
        if (Token::check()) {
            Form::setINPUT([
                InputAlphanumericBuilder::init('termName')
                                        ->build(),
                InputAlphanumericBuilder::init('termDescription')
                                        ->setRequire(FALSE)
                                        ->build(),
            ]);
            
            return Form::inputFilter();
        }
        
        return FALSE;
    }
    
    /**
     * Método que comprueba el nombre de la etiqueta
     * y si existe retorna el nombre concatenado con un número al final.
     *
     * @param string $termName
     * @param int    $id Identificador. Usado en el "Update".
     *
     * @return string
     */
    private function checkName($termName, $id = 0) {
        $name = Term::selectByName($termName);
        $aux  = $name;
        $num  = 0;
        
        while ($name !== FALSE && $name->getID() != $id) {
            $name = $aux->getTermName() . ++$num;
            $name = Term::selectByName($name);
        }
        
        if ($num > 0) {
            $name = $aux->getTermName() . $num;
        }
        
        if ($name === FALSE || (is_object($name) && $name->getID() == $id)) {
            $name = $termName;
        }
        
        return $name;
    }
    
    /**
     * Método llamado por la función UPDATE.
     *
     * @param array $data Lista de argumentos.
     *
     * @return array
     */
    protected function dataUpdate($data) {
        $id   = ArrayHelp::get($data, 'id');
        $term = Term::selectByID($id);
        
        //En caso de que no exista.
        if (empty($term)) {
            Messages::addError('Error. La etiqueta no existe.');
            Helps::redirectRoute();
        }
        
        if (Form::submit('update')) {
            $dataInput = $this->getDataInput();
            
            if ($dataInput === FALSE) {
                Messages::addError('Error al actualizar la etiqueta.');
            } else {
                //Las etiquetas tienen nombres únicos, si ya existe se le agrega un numero al final
                $termName = $this->checkName($dataInput['termName'], $id);
                $update   = new TermUpdate($term, $termName, $dataInput['termDescription']);
                
                //Si ocurre un error la función "$update->update()" retorna FALSE.
                if ($update->update()) {
                    Messages::addSuccess('Etiqueta actualizada correctamente.');
                    $term = $update->getDataUpdate();
                } else {
                    Messages::addError('Error al actualizar la etiqueta.');
                }
            }
        }
        
        return [
            //Instancia Term
            'term' => $term,
        ];
    }
    
    /**
     * Método llamado por la función DELETE.
     *
     * @param array $data Lista de argumentos.
     */
    protected function dataDelete($data) {
        /*
         * Ya que este método no tiene modulo vista propio
         * se carga el modulo vista INDEX, asi que se retornan los datos
         * para esta vista.
         */
        
        $output = FALSE;
        
        if (Token::check()) {
            $delete = new TermDelete($data['id']);
            $output = $delete->delete();
        }
        
        if ($output) {
            Messages::addSuccess('Etiqueta borrada correctamente.');
        } elseif ($output === 0) {
            Messages::addWarning('La etiqueta no existe.');
        } else {
            Messages::addError('Error al borrar la etiqueta.');
        }
    }
    
}
