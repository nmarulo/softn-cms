<?php

/**
 * Controlador de rutas.
 */

namespace SoftnCMS\controllers;

use SoftnCMS\controllers\Request;
use SoftnCMS\controllers\ViewController;

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
        $data = $router->callMethod();
        $router->view($data);
    }

    /**
     * Metodo que crea la instacion del controlador.
     */
    public function instanceController() {
        $requesCtr = $this->request->getController() . 'Controller';
        $controller = \CONTROLLERS . $requesCtr . '.php';

        if (!\is_readable($controller)) {
            $requesCtr = 'IndexController';
        }

        $object = \NAMESPACE_CONTROLLERS . $requesCtr;
        $this->objectCtr = new $object;
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
        return call_user_func_array([$this->objectCtr, $method], $this->request->getArgs());
    }

    /**
     * Metodo que muestra los modulos vista.
     * @param type $data
     */
    public function view($data) {
        $view = new ViewController($this->request, $data);
        $view->render();
    }

}
