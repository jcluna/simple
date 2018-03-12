<?php

/* cargar el manejador de sesiones de php */
session_start();

/* cargar el autoloader de composer */
require_once(dirname(__DIR__) . '/vendor/autoload.php');

/* asociar los handlers para atrapar errores y excepciones no administrados */
error_reporting(E_ALL);
set_error_handler('\Simple\Handler::errors');
set_exception_handler('\Simple\Handler::exceptions');

// cargar el gestor de rutas para despachar las peticiones
$router = new \Simple\Router;
