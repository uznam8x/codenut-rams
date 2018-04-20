<?php

namespace App\Addon\comment;

use App\Helper\Action;
use App\Helper\Data;
use App\Http\Generator\Response;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class Controller {
    use Response;
    private $field = 'comment';

    public function __construct() {
        $this->route();
        $this->initialize();
    }

    public function route() {
        Route::get( '/store/{name}/{xid}/comment', function(Request $request, $name, $xid) {
            return $this->read( $request, $name, $xid );
        } );
        Route::post( '/store/{name}/{xid}/comment', function(Request $request, $name, $xid) {
            return $this->create( $request, $name, $xid );
        } );
        Route::patch( '/store/{name}/comment', function(Request $request, $name) {
            return $this->update( $request, $name );
        } );
        Route::patch( '/store/{name}/{xid}/comment', function(Request $request, $name) {
            return $this->update( $request, $name );
        } );
        Route::delete( '/store/{name}/comment', function(Request $request, $name) {
            return $this->delete( $request, $name );
        } );
        Route::delete( '/store/{name}/{xid}/comment', function(Request $request, $name) {
            return $this->delete( $request, $name );
        } );
    }

    public function info() {
        return array('name' => 'comment', 'description' => 'attach comment at store');
    }

    public function initialize() {
        Action::listen( 'addon.activate', array($this, 'activate') );
        Action::listen( 'addon.deactivate', array($this, 'deactivate') );
        Action::listen( 'store.view', array($this, 'view') );
    }


    public function activate($key) {
        $data = Data::get( $key );
        if (!Schema::hasColumn( 'cr_store_' . $data->store, $this->field . '_cnt' )) {
            Schema::table( 'cr_store_' . $data->store, function(Blueprint $table) {
                $table->integer( 'comment_cnt' )->default( 0 );
            } );

            Schema::create( 'cr_store_' . $data->store . '_' . $this->field, function(Blueprint $table) {
                $table->increments( 'xid' );
                $table->integer( 'pid' );
                $table->string( 'email' );
                $table->mediumText( 'content' );
                $table->timestamps();
            } );
        }
    }

    public function deactivate($key) {
        $data = Data::get( $key );
        if (Schema::hasColumn( 'cr_store_' . $data->store, $this->field . '_cnt' )) {
            Schema::table( 'cr_store_' . $data->store, function(Blueprint $table) {
                $table->dropColumn( 'comment_cnt' );
            } );
            Schema::dropIfExists( 'cr_store_' . $data->store . '_' . $this->field );
        }
    }


    public function create($request, $store, $xid) {
        $param = $request->all();

        $validator = Validator::make( $param, array('email' => 'required|string|email', 'content' => 'required|string') );
        if ($validator->fails()) {
            return $this->error( $validator->errors() );
        }
        $param['pid'] = $xid;
        $param['created_at'] = date( 'Y-m-d H:i:s' );
        $param['updated_at'] = date( 'Y-m-d H:i:s' );
        DB::table( "cr_store_${store}_comment" )->insert( $param );

        $this->setCommentCount( $store, $xid );
        return $this->success();
    }

    private function setCommentCount($store, $xid) {
        $cnt = DB::table( "cr_store_${store}_comment" )->where( 'pid', $xid )->count();
        DB::table( "cr_store_${store}" )->where( 'xid', $xid )->update( array('comment_cnt' => $cnt) );
    }

    private function getCommnet($store, $xid) {
        return DB::table( "cr_store_${store}_comment" )->where( 'pid', $xid )->get();
    }

    public function read($request, $store, $xid) {
        return $this->success( $this->getCommnet( $store, $xid ) );
    }

    public function update($request, $store) {
        $param = $request->all();
        $validator = Validator::make( $param, array('xid' => 'required', 'content' => 'required|string') );
        if ($validator->fails()) {
            return $this->error( $validator->errors() );
        }
        DB::table( "cr_store_${store}_comment" )->where( 'xid', $param['xid'] )->update( array('content' => $param['content']) );
        return $this->success();
    }

    public function delete($request, $store) {
        $param = $request->all();
        $validator = Validator::make( $param, array('xid' => 'required') );
        if ($validator->fails()) {
            return $this->error( $validator->errors() );
        }

        $query = DB::table( "cr_store_${store}_comment" )->where( 'xid', $param['xid'] );
        $result = $query->first();
        $pid = $result->pid;
        $query->delete();
        $this->setCommentCount( $store, $pid );
        return $this->success();
    }

    public function view($key) {
        $data = Data::get( $key );
        $data->result->comment = $this->getCommnet( $data->store, $data->xid );
        Data::set( $key, $data );
    }
}
