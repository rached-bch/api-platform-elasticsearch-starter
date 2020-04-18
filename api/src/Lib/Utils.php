<?php

namespace App\Lib;

class Utils
{
    public function convertStringToSnakeCase(string $str): string
    {
        $str[0] = strtolower($str[0]);
        $func = create_function('$c', 'return "_" . strtolower($c[1]);');

        return preg_replace_callback('/([A-Z])/', $func, $str);
    }

    public function convertStringToCamelCase(string $str): string
    {
        $str[0] = strtoupper($str[0]);
        $func = create_function('$c', 'return strtoupper($c[1]);');

        return preg_replace_callback('/_([a-z])/', $func, $str);
    }
}
