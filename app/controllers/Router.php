<?php

/**
 * Controlador de rutas.
 */

namespace SoftnCMS\controllers;

use SoftnCMS\controllers\Request;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\Login;
use SoftnCMS\models\admin\User;
use SoftnCMS\models\admin\Option;

/**
 * Clase que ejecuta la acción enviada por url.
 *
 * @author Nicolás Marulanda P.
 */
class Router {

    /** @var Request Instacia de la clase Request. */
    private $request;

    /** @var object Instancia del controlador. */
    private $objectCtr;
    private $data;

    /**
     * Contructor.
     * Se crea la instancia Resquest.
     */
    public function __construct() {
        $this->request = new Request();
    }

    /**
     * Metodo que ejecuta las acciones enviadas por url y muestra
     * el modulo vista correspondiente.
     */
    public static function load() {
        $router = new Router();
        $router->instanceController();
        $router->callMethod();
        $router->view();
    }

    /**
     * Metodo que crea la instacion del controlador.
     */
    public function instanceController() {
        if ($this->request->isAdminPanel() && !Login::isLogin()) {
            header('Location: ' . \LOCALHOST . 'login');
            exit();
        }

        if ($this->request->isLoginForm() && Login::isLogin()) {
            header('Location: ' . \LOCALHOST . 'admin');
            exit();
        }

        $requesCtr = $this->request->getController() . 'Controller';
        $this->checkReadableController($requesCtr);
        $namespaceController = $this->getNamespaceController() . $requesCtr;
        $this->objectCtr = new $namespaceController;
    }

    /**
     * Metodo que ejecuta el metodo del controlador.
     * @return type
     */
    public function callMethod() {
        $method = $this->request->getMethod();

        if (!\method_exists($this->objectCtr, $method)) {
            $method = 'index';
        }
        $this->data = call_user_func_array([$this->objectCtr, $method], $this->request->getArgs());
    }

    /**
     * Metodo que muestra los modulos vista.
     */
    public function view() {
        $this->addMoreData();
        $view = new ViewController($this->request, $this->data);
        $view->render();
    }

    private function addMoreData() {
        if ($this->request->isAdminPanel()) {
            $menu = require \CONTROLLERS . 'config' . \DIRECTORY_SEPARATOR . 'LeftbarController.php';
            $this->data['data']['menu'] = $menu;
        }
        
        if (Login::isLogin()) {
            $this->data['data']['userSession'] = User::selectByID($_SESSION['usernameID']);
        }
        $this->data['data']['siteTitle'] = Option::selectByName('optionTitle')->getOptionValue();
        $this->data['data']['siteUrl'] = Option::selectByName('optionSiteUrl')->getOptionValue();
    }

    private function checkReadableController($requesCtr) {
        $controller = $this->getPathController() . "$requesCtr.php";

        if (!\is_readable($controller) && $this->request->isAdminPanel()) {
            header('Location: ' . \LOCALHOST . 'admin');
            exit();
        } elseif (!\is_readable($controller)) {
            header('Location: ' . \LOCALHOST);
            exit();
        }
    }

    private function getPathController() {
        $controller = \CONTROLLERS;

        if ($this->request->isAdminPanel()) {
            $controller = \CONTROLLERS_ADMIN;
        } elseif (!$this->request->isLoginForm() && !$this->request->isRegisterForm() && !$this->request->isLogout()) {
            $controller = \CONTROLLERS_THEMES;
        }
        return $controller;
    }

    private function getNamespaceController() {
        $namespace = \NAMESPACE_CONTROLLERS;

        if ($this->request->isAdminPanel()) {
            $namespace = \NAMESPACE_CONTROLLERS_ADMIN;
        } elseif (!$this->request->isLoginForm() && !$this->request->isRegisterForm() && !$this->request->isLogout()) {
            $namespace = \NAMESPACE_CONTROLLERS_THEMES;
        }
        return $namespace;
    }

}
