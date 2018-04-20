<?php

namespace App\Helper;

use Illuminate\Support\Facades\Event;

class Action {
    public static function listen($type, $callback) {
        Event::listen( get_class( $callback[0] ) . '::' . $type, $callback );
    }

    public static function dispatch($type, $param) {
        event($type, $param);
    }
}