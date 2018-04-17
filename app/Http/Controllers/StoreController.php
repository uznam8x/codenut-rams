<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Validator;

class StoreController extends Mediator {
    protected $table = 'cr_store';

    public function register(Request $request) {
        $param = $request->only( 'name' );
        $validator = Validator::make( $param, ['name' => 'required|string|max:255|unique:cr_store',] );
        if ($validator->fails()) {
            return $this->error( $validator->errors() );
        }

        Schema::create( 'cr_store_' . $param['name'], function(Blueprint $table) {
            $table->increments( 'xid' );
            $table->string( 'email' );
            $table->string( 'subject' );
            $table->string( 'description' );
            $table->timestamps();
        } );

        return $this->response( $this->insert( $param ) );
    }

    public function create(Request $request) {
        //return $this->response( $this->insert() );
    }

    public function read(Request $request, $name = null, $view = false) {
        if ($name) {
            $this->table .= '_' . $name;
        }
        return $this->success($this->select());
    }
}