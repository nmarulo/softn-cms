<?php

/**
 * Controlador del modulo vista.
 */

namespace SoftnCMS\controllers;

use SoftnCMS\controllers\Request;

/**
 * Clase responsable de presentar el modulo vista al usuario.
 *
 * @author Nicolás Marulanda P.
 */
class ViewController {

    private $request;
    private $vars;
    private $view;

    public function __construct(Request $request, $vars) {
        $this->request = $request;
        $this->vars = $vars;
        $this->selectView();
    }

    public function render() {
        $view = \VIEWS . $this->view . '.php';

        if (!\is_readable($view)) {
            $this->view = 'index';
            $view = \VIEWS . $this->view . '.php';
        }
        
        if (\is_array($this->vars)) {
            \extract($this->vars, EXTR_PREFIX_INVALID, 'softn');
        }
        require \VIEWS . 'header.php';
        require \VIEWS . 'topbar.php';
        require \VIEWS . 'leftbar.php';
        require $view;
        require \VIEWS . 'footer.php';
    }

    public function selectView() {
        switch ($this->request->getMethod()) {
            case 'delete':
            case 'index':
                $this->view = $this->request->getController();
                break;
            case 'insert':
            case 'update':
                $this->view = $this->request->getController() . 'insert';
                break;
        }
    }

}
