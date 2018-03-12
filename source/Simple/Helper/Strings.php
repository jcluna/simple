<?php

namespace Simple\Helper;

/**
 * Funciones para trabajar con strings
 */
class Strings {

    /**
     * Transformar un string a StudlyCaps ('hello-world' --> 'HelloWorld')
     *
     * @param string $string
     * @return string 
     */
    public static function toStudlyCaps($string) {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    /**
     * Transformar un string a CamelCase ('hello-world' --> 'helloWorld')
     *
     * @param string $string
     * @return void
     */
    public static function toCamelCase($string) {
        return lcfirst(static::toStudlyCaps($string));
    }

}
