<?php

namespace App;

/**
 * Configuraciones de la aplicación
 */
class Settings {

    /**
     * Presentar los errores no administrados
     *
     * @var boolean Verdadero para presentar en explorador Web, Falso para guardar en archivo log.
     */
    public $showErrors = true; 

    /**
     * Definición del controlador predeterminado
     *
     * @var string Corresponde al nombre de un controlador definido en [App\Controllers]
     */
    public $defaultController = 'Home';

}
