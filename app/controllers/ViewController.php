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

    /**
     *
     * @var Request 
     */
    private $request;

    /**
     *
     * @var array Datos enviados al modulo.
     */
    private $data;

    /**
     *
     * @var string Ruta del modulo vista.
     */
    private $nameView;

    /**
     *
     * @var string 
     */
    private $nameMethodViews;

    public function __construct(Request $request, $data) {
        $this->request = $request;
        $this->data = $data;
        $this->methodViews();
        $this->getNameView();
    }

    public function render() {
        $view = \VIEWS . $this->nameView . '.php';

        if (!\is_readable($view)) {
            $this->nameView = 'index';
            $view = \VIEWS . $this->nameView . '.php';
        }

        if (\is_array($this->data)) {
            \extract($this->data, EXTR_PREFIX_INVALID, 'softn');
        }
        $viewsRequire = \call_user_func([$this, $this->nameMethodViews], $view);

        foreach ($viewsRequire as $value) {
            require $value;
        }
    }

    private function methodViews() {
        $this->nameMethodViews = 'theme';

        if ($this->request->isAdminPanel()) {
            $this->nameMethodViews = 'admin';
        } elseif ($this->request->isLoginForm() || $this->request->isRegisterForm()) {
            $this->nameMethodViews = 'login';
        }
    }

    private function getNameView() {
        switch ($this->request->getMethod()) {
            case 'delete':
            case 'index':
                $this->nameView = $this->request->getController();
                break;
            case 'insert':
            case 'update':
            case 'register':
                $this->nameView = $this->request->getController() . 'insert';
                break;
        }
    }

    private function login($view) {
        return [
            \VIEWS . 'headerlogin.php',
            $view,
            \VIEWS . 'footerlogin.php',
        ];
    }

    private function theme($view) {
        return [
            \THEMES . 'default' . \DIRECTORY_SEPARATOR . 'header.php',
            \THEMES . 'default' . \DIRECTORY_SEPARATOR . 'index.php',
            \THEMES . 'default' . \DIRECTORY_SEPARATOR . 'footer.php',
        ];
    }

    private function admin($view) {
        return [
            \VIEWS . 'header.php',
            \VIEWS . 'topbar.php',
            \VIEWS . 'leftbar.php',
            $view,
            \VIEWS . 'footer.php',
        ];
    }

}
