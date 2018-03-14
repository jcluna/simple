<?php

namespace Simple\Mvc;

/**
 * Clase para gestionar las vistas
 */
abstract class View {

    /**
     * Presentar la vista solicitada
     *
     * @param string $viewName Nombre de la vista
     * @param array $vars Variables para la vista
     * @param object $settings Configuraciones de la aplicación
     * @return void
     */
    public static function template($viewName,  $vars = [], $settings) {
        static $twig = null;
        if($twig === null) {
            // configurar el cargador de plantillas
            $loader = new \Twig_Loader_Filesystem($settings->pathViews);
            $twig = new \Twig_Environment($loader);

            // registrar atributos para la vista
            $twig->addGlobal('brand_name', $settings->appBrandName);
            $twig->addGlobal('base_url', $settings->appBaseUrl);
        }

        // procesar la vista con la plantilla especificada
        echo $twig->render($viewName, $vars);
        exit(); // terminar procesamiento
    }

}