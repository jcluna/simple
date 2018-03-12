<?php

namespace Simple;

/**
 * Manejador de errores y excepciones
 */
class Handler
{

    /**
     * Atrapar los errores PHP y convertirlos excepciones del tipo ErrorException
     *
     * @param [type] $level
     * @param [type] $message
     * @param [type] $file
     * @param [type] $line
     * @return void
     */
    public static function errors($level, $message, $file, $line)
    {
        if (error_reporting() !== 0) {
            throw new \ErrorException($message, 0, $level, $line);
        }
    }

    /**
     * Atraptar todas las excepciones no administradas para presentar en web o guardar en log
     *
     * @param Exception $exception 
     * @return void
     */
    public static function exceptions($exception)
    {
        // cualquier excepción con codigo de error diferente de 404 (Not Found)
        // se reporta como 500 (Internal Server Error) y cambiar la cabecera http
        $code = $exception->getCode();
        if ($code !== 404) {
            $code = 500;
        }
        http_response_code($code);

        // procesar la excepción atrapada
        $settings = new \App\Settings;
        if ($settings->showErrors == true) {
            // presentar el detalle de la excepción en el explorador web
            echo '<h1>Unhandled Exception</h1>';
            echo "<p>Exception Type: '" . get_class($exception) . "'</p>";
            echo "<p>Message: '" . $exception->getMessage() . "'</p>";
            echo '<p>Stack Trace: <pre>' . $exception->getTraceAsString() . '</pre></p>';
            echo "<p>Thrown In '" . $exception->getFile() . "' on lines '" . $exception->getLine() . "'</p>";
        } else {
            // guardar detalle de excepción en archivo log
            $log = dirname(__DIR__) . '/logs/' . date('Y-m-d') . '.txt';
            ini_set('error_log', $log);

            $msg = "Exception Type: '" . get_class($exception) . "' ";
            $msg .= "Message: '" . $exception->getMessage() . "' ";
            $msg .= '\nStack Trace: ' . $exception->getTraceAsString();
            $msg .= "\nThrown In '" . $exception->getFile() . "' on lines '" . $exception->getLine() . "' ";
            $msg .= "\n";
            error_log($msg);
        }
    }

}
