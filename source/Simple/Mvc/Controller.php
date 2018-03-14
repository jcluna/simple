<?php

namespace Simple\Mvc;

use Simple\Mvc\View;

/**
 * Controlador base del sistema
 */
class Controller
{
    /**
     * Configuraciones de la aplicación
     *
     * @var object
     */
    public $settings;

    /**
     * Constructor del controlador base
     */
    public function __construct()
    {
        // implementar en herencia
    }

    /**
     * Cargar una vista
     *
     * @param string $name Nombre de la vista
     * @param array $vars Variables para la vista
     * @return void
     */
    public function view($name, $vars = []) {
        View::template($name, $vars, $this->settings);
    }

    /**
     * Se dispara antes de ejecutar la acción
     *
     * @return boolean
     */
    public function before()
    {
        // implementar en herencia
    }

    /**
     * Se dispara despues de ejecutar la acción
     *
     * @return boolean
     */
    public function after()
    {
        // implementar en herencia
    }

    /**
     * Gestionar la ejecución de Action
     *
     * @param string $name Nombre del metodo a ejecutar
     * @param mixed $args Argumentos del metodo
     * @return void
     */
    public function __call($name, $args)
    {
        $method = $name . 'Action';
        if (method_exists($this, $method)) {
            if ($this->before() !== false) {
                // ejecutar la acción solicitada del controlador
                call_user_func_array([$this, $method], $args);
                return $this->after();
            }
        } else {
            // no existe la acción en el controlador
            $controller = get_class($this);
            throw new \Exception("Method '$method' not found in controller '$controller'");
        }
    }

}
