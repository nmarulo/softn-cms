<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\models\theme;

/**
 * Description of PostsTemplate
 *
 * @author MaruloPC-Desk
 */
class PostsTemplate {

    /** @var array Lista. */
    private $data;

    public function __construct() {
        $this->data = [];
    }

    public function getAll() {
        return $this->data;
    }

    public function getByID($id) {
        return $this->data[$id];
    }

    public function add($data) {
        $data = new PostTemplate($data);
        $this->data[$data->getID()] = $data;
    }

    public function addData($data) {
        foreach ($data as $value) {
            $this->add($value);
        }
    }

}
