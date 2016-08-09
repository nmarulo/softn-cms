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

    /** @var array Lista de datos enviados al modulo vista. */
    private $data;

    /**
     * Contructor.
     * Se crea la instancia Resquest.
     */
    public function __construct() {
        $this->request = new Request();
        $this->data = [];
        $this->initData();
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
            header('Location: ' . $this->data['data']['siteUrl'] . 'login');
            exit();
        }

        if ($this->request->isLoginForm() && Login::isLogin()) {
            header('Location: ' . $this->data['data']['siteUrl'] . 'admin');
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
        $newData = \call_user_func_array([$this->objectCtr, $method], $this->request->getArgs());
        $this->data = \array_merge_recursive($this->data, $newData);
    }

    /**
     * Metodo que muestra los modulos vista.
     */
    public function view() {
        $view = new ViewController($this->request, $this->data);
        $view->render();
    }

    private function initData() {
        if ($this->request->isAdminPanel()) {
            $menu = require \CONTROLLERS_CONFIG . 'LeftbarController.php';
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
            header('Location: ' . $this->data['data']['siteUrl'] . 'admin');
            exit();
        } elseif (!\is_readable($controller)) {
            header('Location: ' . $this->data['data']['siteUrl']);
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
