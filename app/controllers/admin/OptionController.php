<?php

/**
 * Modulo controlador: Pagina de opciones del panel de administración.
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\Controller;
use SoftnCMS\controllers\Form;
use SoftnCMS\controllers\Messages;
use SoftnCMS\controllers\Router;
use SoftnCMS\controllers\Token;
use SoftnCMS\helpers\form\builders\InputAlphabeticBuilder;
use SoftnCMS\helpers\form\builders\InputAlphanumericBuilder;
use SoftnCMS\helpers\form\builders\InputEmailBuilder;
use SoftnCMS\helpers\form\builders\InputIntegerBuilder;
use SoftnCMS\helpers\form\builders\InputUrlBuilder;
use SoftnCMS\helpers\Helps;
use SoftnCMS\models\admin\Option;
use SoftnCMS\models\admin\Options;
use SoftnCMS\models\admin\OptionUpdate;

/**
 * Clase OptionController de la pagina de opciones del panel de administración.
 * @author Nicolás Marulanda P.
 */
class OptionController extends Controller {
    
    /**
     * Método llamado por la función index.
     *
     * @param array $data Lista de argumentos.
     *
     * @return array
     */
    protected function dataIndex($data) {
        //comprueba si hay datos para actualizar.
        $this->dataUpdate();
        $options                          = Options::selectAll()
                                                   ->getAll();
        $options['optionThemeSelectItem'] = array_diff(scandir(THEMES), [
            '..',
            '.',
            'index.php',
        ]);
        
        return $options;
    }
    
    /**
     * Método que actualiza los datos configurables del sitio.
     */
    private function dataUpdate() {
        if (Form::submit('update')) {
            $dataInput = $this->getDataInput();
            
            if ($dataInput === FALSE) {
                Messages::addError("Error al obtener los datos.");
            } else {
                $options = Options::selectAll();
                /*
                 * Usando el indices de "$dataInput", se obtiene
                 * de "$options" cada instancia "OPTION" con sus datos,
                 * luego en "OptionUpdate" se comprueba que datos serán actualizados.
                 */
                $keys       = \array_keys($dataInput);
                $count      = \count($keys);
                $error      = \FALSE;
                $optionName = '';
                
                for ($i = 0; $i < $count && !$error; ++$i) {
                    $optionName  = $keys[$i];
                    $optionValue = $dataInput[$optionName];
                    $option      = $options->getByID($optionName);
                    $update      = new OptionUpdate($option, $optionValue);
                    $error       = !$update->update();
                }
                
                if ($error) {
                    //En caso de error, se muestra el nombre de la opción.
                    Messages::addError("Error al actualizar '$optionName'");
                } else {
                    Messages::addSuccess('Actualizado correctamente.');
                }
            }
        }
    }
    
    /**
     * Método que obtiene los datos de los campos INPUT del formulario.
     * @return array|bool
     */
    private function getDataInput() {
        if (Token::check()) {
            Form::setINPUT([
                InputAlphabeticBuilder::init('optionTitle')
                                      ->build(),
                InputAlphabeticBuilder::init('optionDescription')
                                      ->setRequire(FALSE)
                                      ->build(),
                InputEmailBuilder::init('optionEmailAdmin')
                                 ->build(),
                InputUrlBuilder::init('optionSiteUrl')
                               ->build(),
                InputIntegerBuilder::init('optionPaged')
                                   ->build(),
                InputAlphanumericBuilder::init('optionTheme')
                                        ->build(),
                InputAlphanumericBuilder::init('optionMenu')
                                        ->setRequire(FALSE)
                                        ->build(),
            ]);
            
            return Form::inputFilter();
        }
        
        return FALSE;
    }
    
}
