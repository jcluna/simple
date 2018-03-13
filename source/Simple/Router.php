<?php

namespace Simple;

use Simple\Helper\Strings;

/**
 * Gestos de las rutas de navegación
 */
class Router {

    /**
     * Lista de rutas de navegación registradas
     *
     * @var array
     */
    protected $routes = [];

    /**
     * Lista de parametros de navegación para la petición actual
     *
     * @var array
     */
    protected $params = [];

    // configuraciones de la aplicación
    private $settings;

    /**
     * Constructor de la clase
     *
     * @param Settings $settings Configuraciones de la aplicación
     */
    public function __construct($settings) {
        // -- REGISTRAR LAS RUTAS DE NAVEGACIÓN PREDETERMINADAS -- \\
        $this->settings = $settings;
        
        // cuando no esta definido el controlador y la acción, se asume el controlador y acción por defecto 
        // http://localhost/simple --> http://localhost/simple/home/index
        $this->add('', ['controller' => $settings->defaultController, 'action' => 'index']);

        // cuando esta definido el controlador pero no la acción, se asume la acción por defecto
        // http://localhost/simple/producto --> http://localhost/simple/producto/index
        $this->add('{controller}', ['action' => 'index']);

        // cuando estan definido el controlador y la acción, se procesa directamente
        $this->add('{controller}/{action}');
    }

    /**
     * Registrar ruta de navegación
     *
     * @param string $route Definición de la ruta de navegación
     * @param array $params Array asociativo que puede contener: namespace, controller, action
     * @return void
     */
    public function add($route, $params = []) {
        // convertir la ruta definida en una expresión regular
        // con el objetivo de comparar posteriormente con la ruta 
        // de la petición que se debe despachar por el sistema
        $reg = preg_replace('/\//', '\\/', $route);
        $reg = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $reg);
        $reg = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $reg);
        $reg = '/^' . $reg . '$/i';

        // con este procedimiento se obtiene los siguientes resultados (ejemplos)
        // ej1: entrada: '{controller}' 
        //   --> salida: '/^(?P<controller>[a-z-]+)$/i'
        // ej2: entrada: '{controller}/{action}'
        //   --> salida: '/^(?P<controller>[a-z-]+)\/(?P<action>[a-z-]+)$/i'

        // agregar la expresión regular a la lista junto con los parametros
        $this->routes[$reg] = $params;
    }

    // remover el QueryString (?key1=val1&keyN=valN)
    private function removeQueryString($url) {
        if($url !== '') {
            // separar la url usando el caracter &
            $parts = explode('&', $url, 2);

            // localizar la posión real de la url
            if(strpos($parts[0], '=') === false) {
                return $parts[0];
            } else {
                return ''; // no hay url
            }
        }

        // regresar el valor sin cambios
        return $url;
    }

    // Localizar la ruta de navegación que coincida con la url especificada
    private function match($url) {
        foreach($this->routes as $route => $params) {
            // comparar la url contra la expresión regular de la ruta
            if(preg_match($route, $url, $matches)) {
                // asignar los valores que corresponde a cada parametro
                foreach($matches as $key => $match) {
                    // generalmente se obtendran dos parametros: namespace, controller y action
                    if(is_string($key)) {
                        $params[$key] = $match;
                    }
                }

                // mantener los parametros de la petición actual
                $this->params = $params;
                return true; // ruta localizada
            }
        }

        // no existe ruta para la url especificada
        return false;
    }

    // obtener el namespace para la petición actual
    private function getNamespace() {
        // se toma el namespace predeterminado
        $ns = $this->settings->defaultNamespace;
        if(array_key_exists('namespace', $this->params)) {
            // tomar el namespace definido en la petición actual
            $ns .= $this->params['namespace'] . '\\';
        }

        // regresar el namespace localizado
        return $ns;
    }

    /**
     * Despachar la petición solicitada
     *
     * @param string $url URL de la petición actual
     * @return void
     */
    public function dispatch($url) {
        $requestData = $this->removeQueryString($url); 
        if($this->match($requestData)) {
            // ruta localizada, el controlador relacionado
            $controller = $this->params['controller'];
            $controller = Strings::toStudlyCaps($controller);
            $controller = $this->getNamespace() . $controller; 

            if(class_exists($controller)) {
                // el controlador existe, se crea objeto
                $obj = new $controller();

                // identificar la acción a ejecutar
                $action = $this->params['action'];
                $action = Strings::toCamelCase($action);
                if(preg_match('/action$/i', $action) == 0) {
                    // ejecutar la acción del controlador
                    $obj->$action();
                } else {
                    // no se permite la ejecución directa de la acción
                    throw new \Exception("Method '$action' in controller '$controller' cannot be called directly");
                }
            } else {
                // el controlador no esta definido
                throw new \Exception("Controller class '$controller' not found");
            }
        } else {
            // no hay ruta definida para la petición solicitada
            throw new \Exception("No route matched for '$requestData'");
        }
    }
    
}
