<?php

namespace App;

/**
 * Configuraciones de la aplicación
 */
class Settings {

    /**
     * Define si se presenta o no los errores no administrados
     *
     * @var boolean
     */
    public $showErrors = true;

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

    /**
     * Define la ruta para las vistas
     *
     * @var string
     */
    public $pathViews;

    /**
     * Nombre de marca para la aplicación
     *
     * @var string
     */
    public $appBrandName = 'Simple';

    /**
     * URL base de la aplicación
     *
     * @var string
     */
    public $appBaseUrl = '/simple/';

    /**
     * Crear una instancia del tipo Settings
     */
    public function __construct() {
        // asignar la ruta para el registro de logs
        $this->pathLogs = dirname(__DIR__) . '/logs/';
        $this->pathViews = dirname(__DIR__) . '/Application/Views';
    }

}
