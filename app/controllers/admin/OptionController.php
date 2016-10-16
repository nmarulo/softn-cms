<?php

/**
 * Modulo del controlador de la pagina de configuración.
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\Controller;
use SoftnCMS\controllers\Form;
use SoftnCMS\controllers\Messages;
use SoftnCMS\controllers\Sanitize;
use SoftnCMS\controllers\Token;
use SoftnCMS\models\admin\Options;
use SoftnCMS\models\admin\OptionUpdate;

/**
 * Clase del controlador de la pagina de configuración.
 * @author Nicolás Marulanda P.
 */
class OptionController extends Controller {
    
    /**
     * Metodo llamado por la funcion index.
     *
     * @param array $data Lista de argumentos.
     *
     * @return array
     */
    protected function dataIndex($data) {
        //comprueba si hay datos para actualizar.
        $this->dataUpdate();
        $options = Options::selectAll();
        
        return $options->getAll();
    }
    
    /**
     * Metodo que actualiza los datos configurables del sitio.
     */
    private function dataUpdate() {
        if (Form::submit('update')) {
            $dataInput = $this->getDataInput();
            
            if ($dataInput === FALSE) {
                Messages::addError("Error al obtener los datos.");
            } else {
                $options = Options::selectAll();
                /*
                 * Usando el indices de $DATAINPUT, se obtiene
                 * de $OPTIONS cada instancia OPTION con sus datos,
                 * luego en OPTIONUPDATE se comprueba que datos seran actualizados.
                 */
                $keys       = \array_keys($dataInput);
                $count      = \count($keys);
                $error      = \FALSE;
                $optionName = ''; //En caso de error, contiene el nombre de la opción
                
                for ($i = 0; $i < $count && !$error; ++$i) {
                    $optionName  = $keys[$i];
                    $optionValue = $dataInput[$optionName];
                    $option      = $options->getByID($optionName);
                    $update      = new OptionUpdate($option, $optionValue);
                    $error       = !$update->update();
                }
                
                if ($error) {
                    Messages::addError("Error al actualizar '$optionName'");
                } else {
                    Messages::addSuccess('Actualizado correctamente.');
                }
            }
        }
    }
    
    /**
     * Metodo que obtiene los datos de los campos INPUT del formulario.
     * @return array|bool
     */
    private function getDataInput() {
        if(Token::check()){
            Form::addInputAlphabetic('optionTitle', TRUE);
            Form::addInputAlphabetic('optionDescription');
            Form::addInputEmail('optionEmailAdmin');
            Form::addInputUrl('optionSiteUrl', TRUE);
            Form::addInputInteger('optionPaged', TRUE);
            Form::addInputAlphanumeric('optionTheme', TRUE);
            Form::addInputAlphanumeric('optionMenu');
            
            return Form::postInput();
        }
        
        return FALSE;
    }
    
}
