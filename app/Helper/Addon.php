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
    public static function routes(){
        foreach (glob( app_path( 'Addon' ) . '/*' ) as $path) {
            $name = str_replace( app_path( 'Addon/' ), '', $path );
            $instance = "App\Addon\\${name}\Controller";
            $addon = new $instance();
            Addon::set( $name, array('instance' => get_class( $addon ), 'info' => $addon->info()) );
        }
    }
}