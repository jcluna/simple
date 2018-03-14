<?php

namespace App\Controllers;

use Simple\Mvc\Controller;

/**
 * Controlador Home
 */
class Home extends Controller {

    /**
     * Acción principal del controlador
     *
     * @return void
     */
    public function indexAction() {
        // cargar la vista Index para Home
        $this->view('Home/Index.twig');
    }

}
