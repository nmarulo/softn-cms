<?php

/**
 * Modulo: Rutas de la aplicación.
 */

namespace SoftnCMS\controllers;

/**
 * Clase Router que ejecuta las acciones enviadas por url.
 * Obtiene los controladores y vistas correspondientes.
 * @author Nicolás Marulanda P.
 */
class Router {
    
    /** @var array Lista de rutas. */
    private static $ROUTES = [
        'default'  => '',
        'admin'    => 'admin',
        'login'    => 'login',
        'register' => 'register',
        'logout'   => 'logout',
        'install'  => 'install',
    ];
    
    /** @var array Lista de vistas. Su valor corresponde al indice en "$VIEW_PATH". */
    private static $VIEWS = [
        'default'  => 'theme',
        'admin'    => 'admin',
        'login'    => 'default',
        'register' => 'default',
        'logout'   => 'default',
        'install'  => 'default',
    ];
    
    /** @var array Lista de rutas de los directorios de las vistas. */
    private static $VIEW_PATH = [
        'default' => '',
        'theme'   => '',
        'admin'   => '',
    ];
    
    /** @var array Lista de datos extra ha obtener de la url. */
    private static $ROUTES_ARG = [
        'paged' => 'paged',
        //        'token' => 'token',
    ];
    
    /** @var array Lista de nombres de espacio. */
    private static $NAMESPACE = [
        'default'  => '',
        'admin'    => '',
        'login'    => '',
        'register' => '',
        'logout'   => '',
        'install'  => '',
    ];
    
    /** @var array Lista de ruta de los directorios de las "$ROUTES". */
    private static $PATH = [
        'default'  => '',
        'admin'    => '',
        'login'    => '',
        'register' => '',
        'logout'   => '',
        'install'  => '',
    ];
    
    /** @var array Lista de datos por defecto. */
    private static $DEFAULT = [
        'default' => [
            'controller' => 'Index',
            'method'     => 'index',
            'arguments'  => ['data' => []],
            'namespace'  => '',
            'path'       => '',
            'theme'      => 'default',
        ],
    ];
    
    /** @var array Lista de datos a enviar a los controladores. */
    private static $DATA = [];
    
    /** @var null|Request Instancia Request. */
    private static $REQUEST = NULL;
    
    /** @var array Lista de eventos. */
    private $events;
    
    /**
     * Constructor.
     */
    public function __construct() {
        $this->events  = [
            'beforeCheckRoute' => NULL,
            'beforeCallView'   => NULL,
            'error'            => function() {
                throw new \Exception('Error');
            },
        ];
        self::$REQUEST = new Request();
    }
    
    /**
     * Método que obtiene la lista de vistas.
     * @return array
     */
    public static function getVIEWS() {
        return self::$VIEWS;
    }
    
    /**
     * Método que agrega o actualiza una vista.
     *
     * @param string $key    Indice.
     * @param string $values Valor.
     */
    public static function setVIEWS($key, $values) {
        self::$VIEWS[$key] = $values;
    }
    
    /**
     * Método que obtiene la lista de rutas de los directorios de las vistas.
     * @return array
     */
    public static function getViewPath() {
        return self::$VIEW_PATH;
    }
    
    /**
     * Método que agrega o actualiza una ruta de los directorios de las vistas.
     *
     * @param string $key    Indice.
     * @param string $values Valor.
     */
    public static function setViewPath($key, $values) {
        self::$VIEW_PATH[$key] = $values;
    }
    
    /**
     * Método que obtiene la lista de datos extra a obtener por url.
     * @return array
     */
    public static function getRoutesARG() {
        return self::$ROUTES_ARG;
    }
    
    /**
     * Método que agrega o actualiza uno de los datos extra a obtener por url.
     *
     * @param string $key    Indice.
     * @param string $values Valor.
     */
    public static function setRoutesARG($key, $values) {
        self::$ROUTES_ARG[$key] = $values;
    }
    
    /**
     * Método que la instancia Request.
     * @return null|Request
     */
    public static function getRequest() {
        return self::$REQUEST;
    }
    
    /**
     * Método que obtiene la lista de rutas.
     * @return array
     */
    public static function getRoutes() {
        return self::$ROUTES;
    }
    
    /**
     * Método que agrega o actualiza una ruta.
     *
     * @param string $key    Indice.
     * @param string $values Valor.
     */
    public static function setRoutes($key, $values) {
        self::$ROUTES[$key] = $values;
    }
    
    /**
     * Método que obtiene la lista de ruta de los directorios de las "$ROUTES".
     * @return array
     */
    public static function getPath() {
        return self::$PATH;
    }
    
    /**
     * Método que agrega o actualiza una ruta de los directorios de las "$ROUTES".
     *
     * @param string $key    Indice.
     * @param string $values Valor.
     */
    public static function setPath($key, $values) {
        self::$PATH[$key] = $values;
    }
    
    /**
     * Método que obtiene la lista de los nombres de espacio.
     * @return array
     */
    public static function getNamespaces() {
        return self::$NAMESPACE;
    }
    
    /**
     * Método que agrega o actualiza un nombre de espacio.
     *
     * @param string $key    Indice.
     * @param string $values Valor.
     */
    public static function setNamespace($key, $values) {
        self::$NAMESPACE[$key] = $values;
    }
    
    /**
     * Método que agrega o actualiza la lista de datos por defecto.
     *
     * @param string $key Indice.
     * @param string $controller
     * @param string $method
     * @param array  $argument
     */
    public static function setDefault($key, $controller = 'Index', $method = 'index', $argument = ['']) {
        self::$DEFAULT[$key] = [
            'controller' => $controller,
            'method'     => $method,
            'argument'   => $argument,
        ];
    }
    
    /**
     * Método que obtiene los datos a enviar a los controladores.
     * @return mixed
     */
    public static function getDATA() {
        return self::$DATA['data'];
    }
    
    /**
     * Método que agrega o actualiza los datos a enviar a los controladores.
     *
     * @param string $key    Indice.
     * @param string $values Valor.
     */
    public static function setDATA($key, $values) {
        self::$DATA['data'][$key] = $values;
    }
    
    /**
     * Método que agrega o actualiza la lista de eventos.
     *
     * @param string   $key      Indice.
     * @param \Closure $callback Función.
     */
    public function setEvent($key, $callback) {
        $this->events[$key] = $callback;
    }
    
    /**
     * Método que llama a los controladores y carga las vistas.
     */
    public function load() {
        $this->event('beforeCheckRoute');
        
        $instanceController = $this->getInstanceController();
        $method             = $this->getMethod($instanceController);
        $argument           = $this->getArguments();
        
        $this->event();
        
        $data       = call_user_func_array([
            $instanceController,
            $method,
        ], $argument);
        self::$DATA = array_merge_recursive(self::$DATA, $data);
        
        $this->event('beforeCallView');
        
        $view = new ViewController(self::$REQUEST, self::$DATA);
        $view->render();
    }
    
    /**
     * Método que ejecuta un evento.
     *
     * @param null|string $event [Opcional] Nombre del evento. Si es NULL, si comprueba si existe algún evento
     *                           vinculado a la ruta o al controlador actual.
     */
    private function event($event = NULL) {
        $keyEvent = $event;
        
        if (empty($event)) {
            $controller = strtolower(self::$REQUEST->getController());
            $key        = array_search(self::$REQUEST->getRoute(), self::$ROUTES, TRUE);
            if (empty($key)) {
                $key = array_search($controller, self::$ROUTES, TRUE);
            }
            
            $keyEvent = self::$ROUTES[$key];
        }
        
        if (!empty($keyEvent)) {
            $keysEvent = array_keys($this->events);
            $key       = array_search($keyEvent, $keysEvent);
            
            if ($key !== FALSE) {
                $callback = $this->events[$keysEvent[$key]];
                is_callable($callback) ? $callback() : FALSE;
            }
        }
    }
    
    /**
     * Método que obtiene la instancia del controlador.
     * @return mixed
     */
    private function getInstanceController() {
        $key        = $this->getKeyRoutes();
        $controller = self::$REQUEST->getController();
        
        $path      = self::$PATH[$key];
        $namespace = self::$NAMESPACE[$key];
        
        if (empty($controller)) {
            $keyDefault = $key;
            
            if (!array_key_exists($keyDefault, self::$DEFAULT)) {
                $keyDefault = 'default';
            }
            
            $controller = self::$DEFAULT[$keyDefault]['controller'];
        }
        
        self::$REQUEST->setController($controller);
        
        $controller .= 'Controller';
        
        $pathController = $path . $controller . '.php';
        
        if (!file_exists($pathController)) {
            $this->event('error');
        }
        
        $ctrNamespace = $namespace . $controller;
        
        return new $ctrNamespace();
    }
    
    /**
     * Método que obtiene el indice de la lista de ruta ("$ROUTES") según la ruta actual, indicada en
     * "$REQUEST->getRoute()", si no existe retorna el indice "default".
     * @return mixed|string
     */
    private function getKeyRoutes() {
        $key = array_search(self::$REQUEST->getRoute(), self::$ROUTES, TRUE);
        
        if ($key === FALSE) {
            $key = 'default';
        }
        
        return $key;
    }
    
    /**
     * Método que obtiene el nombre del método.
     *
     * @param $instanceController
     *
     * @return string
     */
    private function getMethod($instanceController) {
        $method = self::$REQUEST->getMethod();
        
        if (!method_exists($instanceController, self::$REQUEST->getMethod())) {
            
            $key = $this->getKeyRoutes();
            
            if (!array_key_exists($key, self::$DEFAULT)) {
                $key = 'default';
            }
            
            $method = self::$DEFAULT[$key]['method'];
            self::$REQUEST->setMethod($method);
        }
        
        return $method;
    }
    
    /**
     * Método que obtiene los argumentos.
     * @return array
     */
    private function getArguments() {
        $arguments = self::$REQUEST->getArgs();
        
        if (empty($arguments)) {
            $key = $this->getKeyRoutes();
            
            if (!array_key_exists($key, self::$DEFAULT)) {
                $key = 'default';
            }
            
            $arguments = self::$DEFAULT[$key]['arguments'];
            self::$REQUEST->setArgs($arguments);
        }
        
        return $arguments;
    }
    
}
