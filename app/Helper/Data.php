<?php

namespace App\Helper;
class Data {
    private static $map = array();

    public static function get($key) {
        return (object) self::$map[$key];
    }
    public static function set($key, $value) {
        self::$map[$key] = $value;
    }
}