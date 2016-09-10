<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\controllers\themes;

use SoftnCMS\models\admin\Term;

/**
 * Description of TermTemplate
 *
 * @author Nicolás Marulanda P.
 */
class TermTemplate {

    /** @var Term Instancia. */
    private $term;

    public function __construct($term) {
        $this->term = $term;
    }

    public function getID() {
        return $this->term->getID();
    }

    public function getInstance() {
        return $this->term;
    }

    public function getTermUrl($isEcho = \TRUE) {
        global $urlSite;

        if (!$isEcho) {

            return $urlSite . 'term/' . $this->getID();
        }

        echo $urlSite . 'term/' . $this->getID();
    }

    public function getCategoryID($isEcho = \TRUE, $addID = 'term-') {
        if (!$isEcho) {

            return $addID . $this->getID();
        }

        echo $addID . $this->getID();
    }

    public function getTermName($isEcho = \TRUE) {
        if (!$isEcho) {

            return $this->term->getTermName();
        }

        echo $this->term->getTermName();
    }

}
