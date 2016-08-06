<?php

/**
 * 
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\models\admin\Posts;
use SoftnCMS\models\admin\Users;

/**
 * Description of SoftN
 *
 * @author Nicolás Marulanda P.
 */
class IndexController {

    public function __construct() {
        /*
         * --- count
         * post
         * page
         * comment
         * category
         * user
         * --- ultimas actulizaciones github
         * --- ultimos post
         * -- comentarios recientes
         */
    }

    public function index() {
        return ['data' => $this->dataIndex()];
    }

    private function dataIndex() {
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

    private function lastUpdateGitHub() {
        $lengTitle = 20;
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
