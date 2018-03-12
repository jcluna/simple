<?php

namespace Simple;

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
     * Constructor de la clase
     */
    public function __construct() {
        // -- REGISTRAR LAS RUTAS DE NAVEGACIÓN PREDETERMINADAS -- \\
        
        // cuando no esta definido el controlador y la acción, se asume el controlador y acción por defecto 
        // http://localhost/simple --> http://localhost/simple/home/index
        $settings = new \App\Settings;
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
     * @param array $params Controlador y acción relacionados
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
        $routes[$reg] = $params;
    }
}