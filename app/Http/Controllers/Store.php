<?php

namespace App\Http\Controllers;

class Store extends Controller {
    public function create($name, $field){

    }
    public function read(){

    }
    public function update(){

    }
    public function delete(){

    }
    protected function response($code = 200, $data = array() ){
        return json_encode( array('code' => $code, 'data' => $data), JSON_UNESCAPED_UNICODE );
    }
}