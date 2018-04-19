<?php

namespace App\Http\Generator;
trait Response {
    public function forbidden($data = array()) {
        return $this->format( 403, $data );
    }

    public function error($data = array()) {
        return $this->format( 500, $data );
    }

    public function success($data = array()) {
        return $this->format( 200, $data );
    }

    public function format($code = 200, $data = array()) {
        return json_encode( array('code' => $code, 'data' => $data), JSON_UNESCAPED_UNICODE );
    }
}
