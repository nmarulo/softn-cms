<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\models\theme;

use SoftnCMS\models\admin\Category;

/**
 * Description of CategoryTemplate
 *
 * @author Nicolás Marulanda P.
 */
class CategoryTemplate {

    /** @var Category Instancia. */
    private $category;

    public function __construct($category) {
        $this->category = $category;
    }

    public function getID() {
        return $this->category->getID();
    }

    public function getInstance() {
        return $this->category;
    }

    public function getCategoryUrl($isEcho = \TRUE) {
        global $urlSite;

        if (!$isEcho) {

            return $urlSite . 'category/' . $this->getID();
        }

        echo $urlSite . 'category/' . $this->getID();
    }

    public function getCategoryID($isEcho = \TRUE, $addID = 'category-') {
        if (!$isEcho) {

            return $addID . $this->getID();
        }

        echo $addID . $this->getID();
    }

    public function getCategoryName($isEcho = \TRUE) {
        if (!$isEcho) {

            return $this->category->getCategoryName();
        }

        echo $this->category->getCategoryName();
    }
    
    public function getCategoryDescription($isEcho = \TRUE) {
        if (!$isEcho) {
        
            return $this->category->getCategoryDescription();
        }
    
        echo $this->category->getCategoryDescription();
        
    }

}
