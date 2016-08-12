<?php

/**
 * Modulo del controlador de la pagina de inicio del panel de administración.
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\Controller;
use SoftnCMS\models\admin\Posts;
use SoftnCMS\models\admin\Users;

/**
 * Clase del controlador de la pagina de inicio del panel de administración.
 *
 * @author Nicolás Marulanda P.
 */
class IndexController extends Controller {

    /**
     * Metodo llamado por la funcion index.
     * @return array
     */
    protected function dataIndex() {
        $posts = new Posts();
        $users = new Users();

        return [
            'github' => $this->lastUpdateGitHub(),
            'lastPosts' => $posts->lastPosts(5),
            'lastComments' => [],
            'count' => [
                'post' => $posts->count(),
                'page' => 0,
                'category' => 0,
                'comment' => 0,
                'user' => $users->count(),
            ],
        ];
    }

    /**
     * Metod que obtiene las actualizaciones del GitHub.
     * @return array
     */
    private function lastUpdateGitHub() {
        $github = \simplexml_load_file('https://github.com/nmarulo/softn-cms/commits/develop.atom');
        $github = \get_object_vars($github);
        $leng = \count($github['entry']);
        $forEnd = $leng > 5 ? 5 : $leng;
        $dataGitHub = [
            'lastUpdate' => $github['updated'],
            'entry' => [],
        ];

        /*
         * Indices de $elements "id", "link"=>"@attributes"=>"href", "title",
         * "updated", "author"=>"name", "author"=>"uri", "content"
         */
        for ($i = 0; $i < $forEnd; ++$i) {
            $element = \get_object_vars($github['entry'][$i]);
            $element['link'] = \get_object_vars($element['link']);
            $element['author'] = \get_object_vars($element['author']);
            $dataGitHub['entry'][] = [
                'authorName' => $element['author']['name'],
                'authorUri' => $element['author']['uri'],
                'linkHref' => $element['link']['@attributes']['href'],
                'title' => $element['title'],
            ];
        }
        
        return $dataGitHub;
    }

}
