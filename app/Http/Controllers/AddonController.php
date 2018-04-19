<?php

namespace App\Http\Controllers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use App\Http\Generator\Response;
use Mockery\Exception;

class AddonController extends Controller {
    use Response;

    private function validator(Request $request, $name) {
        $param = $request->only( 'store' );
        $validator = Validator::make( $param, ['store' => 'required|string'] );
        if ($validator->fails()) {
            return (object)array('code' => 500, 'message' => $validator->errors());
        }

        $query = DB::table( 'cr_agent' )->where( 'store', $param['store'] );
        $exist = $query->count();
        if (!$exist) {
            return (object)array('code' => 500, 'message' => $param['store'] . ' is not registered');
        }

        $result = $query->first();
        return (object)array('code' => 200, 'data' => $result->addon, 'store' => $param['store'], 'query' => $query);
    }

    public function create(Request $request, $name) {
        $result = $this->validator( $request, $name );
        if ($result->code === 200) {
            $addons = json_decode( $result->data );
            $index = array_search( $name, $addons );
            if ($index === false) {
                array_push( $addons, $name );
            }
            $result->query->update( ['addon' => json_encode( $addons, JSON_UNESCAPED_UNICODE )] );

            // create addon field
            $instance = "App\Addon\\" . $name . "\Controller";
            $controller = new $instance();
            $feedback = (object) $controller->register( (object) array('table' => 'cr_store_' . $result->store) );
            if ($feedback->code == 200) {
                return $this->success();
            } else {
                return $this->error( $feedback->message );
            }
        } else {
            return $this->error( $result->message );
        }
    }

    public function delete(Request $request, $name) {
        $result = $this->validator( $request, $name );
        if ($result->code === 200) {
            $addons = json_decode( $result->data );
            $index = array_search( $name, $addons );
            if ($index !== false) {
                array_splice( $addons, $index, 1 );
            }
            $result->query->update( ['addon' => json_encode( $addons, JSON_UNESCAPED_UNICODE )] );
            return $this->success();
        } else {
            return $this->error( $result->message );
        }
    }

    public function read(Request $request, $name = null) {
        if ($name) {
            try {
                $controller = str_replace( '/', '\\', '/App/Addon/' . $name . '/Controller' );
                $instance = new $controller();
                return $this->success( $instance->info() );
            } catch (Exception $e) {
                return $this->error( array('message' => 'error addon') );
            }
        } else {
            $arr = array();
            foreach (glob( app_path( 'Addon/*' ) ) as $filename) {
                array_push( $arr, str_replace( app_path( 'Addon/' ), '', $filename ) );
            }
            return $this->success( $arr );
        }
    }
}