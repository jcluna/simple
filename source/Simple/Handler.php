<?php

namespace Simple;

/**
 * Manejador de errores y excepciones
 */
class Handler
{
    private $settings;

    /**
     * Crear una instancia del manejador de errores y excepciones
     *
     * @param object $settings Configuraciones de la aplicación
     */
    public function __construct($settings) {
        $this->settings = $settings;

        // atrapar todos los errores y excepciones no administradas
        error_reporting(E_ALL);
        set_error_handler([$this, 'customErrorHandler']);
        set_exception_handler([$this, 'customExceptionHandler']);
    }

    /**
     * Convertir los errores a excepciones del tipo ErrorException
     *
     * @param int $level Nivel del error
     * @param string $message Mensaje del error
     * @param string $file Archivo origen del error
     * @param int $line Numéro de linea origen del error
     * @return void
     */
    public function customErrorHandler($level, $message, $file, $line)
    {
        if (error_reporting() !== 0) {
            throw new \ErrorException($message, 0, $level, $line);
        }
    }

    /**
     * Procesar las excepciones no administradas
     *
     * @param mixed $exception Excepción no administrada
     * @return void
     */
    public function customExceptionHandler($exception)
    {
        // cualquier excepción con codigo de error diferente de 404 (Not Found)
        // se reporta como 500 (Internal Server Error) y cambiar la cabecera http
        $code = $exception->getCode();
        if ($code !== 404) {
            $code = 500;
        }
        http_response_code($code);

        // procesar la excepción atrapada
        if ($this->settings->showErrors == true) {
            // presentar el detalle de la excepción en el explorador web
            echo '<h1>Unhandled Exception</h1>';
            echo "<p>Exception Type: '" . get_class($exception) . "'</p>";
            echo "<p>Message: '" . $exception->getMessage() . "'</p>";
            echo '<p>Stack Trace: <pre>' . $exception->getTraceAsString() . '</pre></p>';
            echo "<p>Thrown In '" . $exception->getFile() . "' on lines '" . $exception->getLine() . "'</p>";
        } else {
            // guardar detalle de excepción en archivo log
            $log = $this->settings->pathLogs . date('Y-m-d') . '.txt';
            ini_set('error_log', $log);

            $msg = "Exception Type: '" . get_class($exception) . "'\n";
            $msg .= "Message: '" . $exception->getMessage() . "' ";
            $msg .= "\nStack Trace: \n" . str_replace('#', "  #", $exception->getTraceAsString());
            $msg .= "\nThrown In '" . $exception->getFile() . "' on lines '" . $exception->getLine() . "' ";
            $msg .= "\n\n";
            
            // gardar en archivo log
            error_log($msg);
        }
    }

}
