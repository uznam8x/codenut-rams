<?php

namespace App\Http\Controllers;

use App\Events\AddonEvent;
use App\Events\Event;
use Illuminate\Support\Facades\DB;

class Agent extends Controller {
    private $status = array('200' => 'success', '403' => 'forbidden', '500' => 'error');
    protected $table = '';
    protected $validate = array('user' => array(), 'store' => array('name' => 'required|string|max:255|unique:cr_store'));

    protected function insert($data) {
        $prop = array('created_at' => date( 'Y-m-d H:i:s' ), 'updated_at' => date( 'Y-m-d H:i:s' ));
        foreach ($data as $key => $value) {
            $prop[$key] = $value;
        }
        /*
        $name = str_replace( 'cr_store_', '', $this->table );
        $result = DB::table( 'cr_store' )->select( 'addons' )->where( 'name', $name )->first();
        if (count( $result )) {
            $addons = json_decode( $result->addons );
            foreach ($addons as $key => $value) {
                $controller = "App\Addon\\${value}\Controller";
                $instance = new $controller();

                if (method_exists( $instance, 'insert' )) {
                    $param = $instance->insert((object) array('table' => $this->table, 'prop' => $prop) );
                    $prop = $param->prop;
                }
            }
        }
        */
        return DB::table( $this->table )->insert( $prop );
    }

    protected function select() {
        $result = DB::table( $this->table )->get();
        for ($i = 0, $len = count( $result ); $i < $len; $i++) {
            foreach ($result[$i] as $key => $value) {
                if (strpos( $key, 'password' ) !== false) {
                    unset( $result[$i]->$key );
                }
            }
        }
        //$e = event( new AddonEvent( array('type' => 'select', 'data' => array()) ) );
        return $result;
    }

    protected function error($data = null) {
        if (!$data) {
            $data = array('message' => $this->status['500']);
        }
        return $this->response( 500, $data );
    }

    protected function success($data = null) {
        if (!$data) {
            $data = array('message' => $this->status['200']);
        }
        return $this->response( 200, $data );
    }

    protected function response($code, $data = null) {
        $code = ($code === true || $code === 200 || $code === 1) ? 200 : 500;
        $prop = array('code' => $code);
        $prop['data'] = ($data) ? $data : array('message' => $this->status[$code]);
        return json_encode( $prop, JSON_UNESCAPED_UNICODE );
    }
}