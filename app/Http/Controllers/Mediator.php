<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class Mediator extends Controller {
    private $status = array('200' => 'success', '403' => 'forbidden', '500' => 'error');
    protected $table = '';
    protected function insert($data) {
        $prop = array(
            'created_at'=> date('Y-m-d H:i:s'),
            'updated_at'=> date('Y-m-d H:i:s')
        );
        foreach ($data as $key => $value){
            $prop[$key] = $value;
        }

        return DB::table( $this->table )->insert( $prop );
    }

    protected function select(){
        $result = DB::table($this->table)->get();
        for($i = 0, $len = count($result); $i<$len; $i++){
            foreach ($result[$i] as $key => $value){
                if( !$value){
                    unset( $result[$i]->$key );
                }
                if( strpos($key, 'password') !== false ){
                    unset( $result[$i]->$key );
                }
            }
        }
        return $result;
    }

    protected function error($data = null) {
        if(!$data) $data = array('message' => $this->status['500']);
        return $this->response( 500, $data );
    }

    protected function success($data = null) {
        if(!$data) $data = array('message' => $this->status['200']);
        return $this->response( 200, $data );
    }

    protected function response($code, $data = null) {
        $code = ($code === true || $code === 200) ? 200 : 500;
        $prop = array('code' => $code);
        $prop['data'] = ($data) ? $data : array('message' => $this->status[$code]);
        return json_encode( $prop, JSON_UNESCAPED_UNICODE );
    }
}