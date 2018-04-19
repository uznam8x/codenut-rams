<?php

namespace App\Helper;
class Addon {
    private static $map = array();

    public static function get($name) {
        return (object) self::$map[$name];
    }
    public static function set($name, $value) {
        self::$map[$name] = $value;
    }
}