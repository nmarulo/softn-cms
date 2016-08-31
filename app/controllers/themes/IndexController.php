<?php

/**
 * Modulo del controlador de los temas de la aplicación.
 */

namespace SoftnCMS\controllers\themes;

use SoftnCMS\controllers\Controller;
use SoftnCMS\models\admin\Posts;

/**
 * Clase controlador de los temas.
 *
 * @author Nicolás Marulanda P.
 */
class IndexController extends Controller {

    /**
     * Metodo llamado por la función INDEX.
     * @return array
     */
    protected function dataIndex() {
        $posts = Posts::selectAll();
        $output = [];
        
        if($posts !== \FALSE){
            $output = $posts->getAll();
        }

        return [
            'posts' => $output
        ];
    }

}
