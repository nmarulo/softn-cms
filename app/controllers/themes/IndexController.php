<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\controllers\themes;

use SoftnCMS\models\admin\Posts;

/**
 * Description of IndexController
 *
 * @author Nicolás Marulanda P.
 */
class IndexController {

    public function index() {
        return ['data' => $this->dataIndex()];
    }

    private function dataIndex() {
        $posts = Posts::selectAll();
        return [
            'posts' => $posts->getPosts()
        ];
    }

}
