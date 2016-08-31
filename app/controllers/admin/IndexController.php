<?php

/**
 * Modulo del controlador de la pagina de inicio del panel de administración.
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\Controller;
use SoftnCMS\models\admin\Posts;
use SoftnCMS\models\admin\Users;
use SoftnCMS\models\admin\Comments;
use SoftnCMS\models\admin\Categories;

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
        $comments = new Comments();
        $categories = new Categories();
        $lastPosts = $posts->lastData(5);
        $lastComments = $comments->lastData(5);

        foreach ($lastPosts as $value) {
            $title = $value->getPostTitle();

            if (isset($title{30})) {
                $title = substr($title, 0, 30) . ' [...]';
            }
            $value->setPostTitle($title);
        }

        foreach ($lastComments as $value) {
            //Borra las etiquetas html
            $contents = strip_tags($value->getCommentContents());

            if (isset($contents{30})) {
                $contents = substr($contents, 0, 30) . ' [...]';
            }
            $value->setCommentContents($contents);
        }

        return [
            'github' => $this->lastUpdateGitHub(),
            'lastPosts' => $lastPosts,
            'lastComments' => $lastComments,
            'count' => [
                'post' => $posts->count(),
                'page' => 0,
                'category' => $categories->count(),
                'comment' => $comments->count(),
                'user' => $users->count(),
            ],
        ];
    }

    /**
     * Metodo que obtiene las ultimas 5 actualizaciones de las rama Master y la 
     * rama Develop de GitHub.
     * @return array
     */
    private function lastUpdateGitHub() {
        //api https://api.github.com/repos/nmarulo/softn-cms/branches/develop
        $xmlUrlDevelop = 'https://github.com/nmarulo/softn-cms/commits/develop.atom';
        $xmlUrlMaster = 'https://github.com/nmarulo/softn-cms/commits/master.atom';

        return [
            'master' => $this->xmlGitHub($xmlUrlMaster),
            'develop' => $this->xmlGitHub($xmlUrlDevelop),
        ];
    }

    /**
     * Metod que obtiene las actualizaciones de la url de GitHub.
     * @param string $xmlUrl
     * @return array
     */
    private function xmlGitHub($xmlUrl) {
        $github = \get_object_vars(\simplexml_load_file($xmlUrl));
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
