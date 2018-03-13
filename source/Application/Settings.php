<?php

namespace App;

/**
 * Configuraciones de la aplicación
 */
class Settings {

    public function __construct() {
        // asignar la ruta para el registro de logs
        $this->pathLogs = dirname(__DIR__) . '/logs/';
    }

    /**
     * Define si se presenta o no los errores no administrados
     *
     * @var boolean
     */
    public $showErrors = false;

    /**
     * Define la ruta para el registro de logs
     *
     * @var string
     */
    public $pathLogs;

    /**
     * Define el controlador predeterminado
     *
     * @var string
     */
    public $defaultController = 'Home';

    /**
     * Define el namespace predeterminado
     *
     * @var string
     */
    public $defaultNamespace = 'App\Controllers\\';

}
