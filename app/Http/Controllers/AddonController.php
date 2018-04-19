<?php

namespace App\Http\Controllers;

use App\Helper\Addon;
use App\Helper\Announcement;
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
            $param = $request->all();
            unset( $param['store'] );

            $addons = json_decode( $result->data );
            $index = array_search( $name, $addons );
            if ($index === false) {
                array_push( $addons, $name );
            }
            $result->query->update( ['addon' => json_encode( $addons, JSON_UNESCAPED_UNICODE )] );

            // create addon field
            $uniq = uniqid();
            Announcement::set( $uniq, array('addon' => $name, 'store' => $result->store, 'param' => $param, 'error' => array()) );
            event( 'addon.activate', $uniq );

            $data = Announcement::get( $uniq );
            if(count( $data->error )){
                return $this->error($data->error);
            } else {
                return $this->success();
            }
        } else {
            return $this->error( $result->message );
        }
    }

    public function delete(Request $request, $name) {
        $result = $this->validator( $request, $name );
        if ($result->code === 200) {
            $param = $request->all();
            unset( $param['store'] );

            $addons = json_decode( $result->data );
            $index = array_search( $name, $addons );
            if ($index !== false) {
                array_splice( $addons, $index, 1 );
            }
            $result->query->update( ['addon' => json_encode( $addons, JSON_UNESCAPED_UNICODE )] );

            // create addon field
            $uniq = uniqid();
            Announcement::set( $uniq, array('addon' => $name, 'store' => $result->store, 'param' => $param, 'error' => array()) );
            event( 'addon.deactivate', $uniq );

            $data = Announcement::get( $uniq );
            if(count( $data->error )){
                return $this->error($data->error);
            } else {
                return $this->success();
            }
        } else {
            return $this->error( $result->message );
        }
    }

    public function read(Request $request, $name = null) {
        if ($name) {
            try {
                return $this->success( Addon::get( $name )->info );
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