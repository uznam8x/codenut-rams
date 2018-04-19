<?php

namespace App\Http\Controllers;

use App\Events\AddonEvent;
use App\Helper\Announcement;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use App\Http\Generator\Response;
use Mockery\Exception;

class StoreController extends Controller {
    use Response;

    private $prefix = 'cr_store_';

    /*
    public function addon($type, $name, $xid = null, $param = null) {
        $result = DB::table( 'cr_agent' )->where( 'store', $name )->first();
        if (count( $result )) {
            $addons = json_decode( $result->addon );
            if (count( $addons )) {
                foreach ($addons as $key => $value) {
                    $instance = "App\Addon\\" . $value . "\Controller";
                    $controller = new $instance();
                    try {
                        call_user_func( array($controller, $type), 'cr_store_' . $name, $xid, $param );
                    } catch (Exception $e) {
                    }
                }
            }
        }
    }*/

    public function create(Request $request, $name) {
        $param = $request->all();
        $validator = Validator::make( $param, array('email' => 'required|string|email', 'subject' => 'required|string', 'description' => 'required|string') );
        if ($validator->fails()) {
            return $this->error( $validator->errors() );
        }
        $param['created_at'] = date( 'Y-m-d H:i:s' );
        $param['updated_at'] = date( 'Y-m-d H:i:s' );
        DB::table( $this->prefix . $name )->insert( $param );
        $this->addon( 'create', $name );
        return $this->success();
    }

    public function read(Request $request, $name = null) {
        if ($name) {
            $query = DB::table( $this->prefix . $name );

            $select = ['xid', 'email', 'subject', 'updated_at'];
            $param = $request->only( 'include' );
            if ($param) {
                $arr = explode( ',', $param['include'] );
                $colums = Schema::getColumnListing( $this->prefix . $name );
                foreach ($arr as $key => $value) {
                    $index = array_search( $value, $colums );
                    if ($index >= -1) {
                        array_push( $select, $value );
                    }
                }
            }
            return $this->success( $query->select( $select )->get() );
        } else {
            return $this->error( array('name' => 'missing name') );
        }
    }

    public function view(Request $request, $name, $xid) {
        if ($name) {
            $param = $request->only( 'exclude' );

            $query = DB::table( $this->prefix . $name )->where( 'xid', $xid );
            if (isset( $param['exclude'] )) {
                $arr = explode( ',', $param['exclude'] );
                $colums = Schema::getColumnListing( $this->prefix . $name );
                foreach ($arr as $key => $value) {
                    $index = array_search( $value, $colums );
                    if ($index >= -1) {
                        array_splice( $colums, $index, 1 );
                    }
                }
                $query = $query->select( $colums );
            }

            $uniq = uniqid();
            Announcement::set( $uniq, array('store' => $name, 'xid' => $xid, 'param' => $param) );
            event( 'store.view', $uniq );
            $param = Announcement::get( $uniq );
            return $this->success( $query->first() );
        } else {
            return $this->error( array('name' => 'missing name') );
        }
    }

    public function update(Request $request, $name, $xid) {
        $param = $request->all();

        $except = ['xid', 'email'];
        foreach ($param as $key => $value) {
            $index = array_search( $key, $except );
            if ($index !== false) {
                unset( $param[$key] );
            }
        }


        // broadcast
        $uniq = uniqid();
        Announcement::set( $uniq, array('store' => $name, 'xid' => $xid, 'param' => $param) );
        event( 'store.update', $uniq );
        $param = Announcement::get( $uniq );


        // addon update
        //$this->addon( 'update', $name, $xid, $param );

        $colums = Schema::getColumnListing( 'cr_store_' . $name );
        foreach ($param as $key => $value) {
            $index = array_search( $key, $colums );
            if ($index === false) {
                unset( $param[$key] );
            }
        }

        if (count( $param )) {
            $param['updated_at'] = date( 'Y-m-d H:i:s' );
            DB::table( 'cr_store_' . $name )->where( 'xid', $xid )->update( $param );
        }

        return $this->success();
    }
}