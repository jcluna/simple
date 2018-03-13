<?php

/* cargar el manejador de sesiones de php */
session_start();

/* cargar el autoloader de composer */
require_once(dirname(__DIR__) . '/vendor/autoload.php');

/* cargar la configuración de la aplicación */
$settings = new \App\Settings;

/* asociar el gestor de errores y excepciones */
$handler = new \Simple\Handler($settings);

// cargar el gestor de rutas para despachar las peticiones
$router = new \Simple\Router($settings);
$router->dispatch(filter_input(INPUT_SERVER, 'QUERY_STRING'));
