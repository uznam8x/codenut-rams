<?php

namespace App\Http\Controllers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use App\Http\Generator\Response;

class AgentController extends Controller {
    use Response;
    private $validate = array('name' => 'required|string|max:255|unique:cr_agent', 'addon' => 'string');

    public function create(Request $request) {
        $param = $request->only( ['name', 'addon'] );
        $validator = Validator::make( $param, $this->validate );
        if ($validator->fails()) {
            return $this->error( $validator->errors() );
        }


        if (!isset( $param['addon'] )) {
            $param['addon'] = '[]';
        }

        // create store table
        Schema::create( 'cr_store_' . $param['name'], function(Blueprint $table) {
            $table->increments( 'xid' );
            $table->string( 'email' );
            $table->string( 'subject' );
            $table->text( 'description' );
            $table->timestamps();
        } );

        // register store name
        $param['created_at'] = date( 'Y-m-d H:i:s' );
        $param['updated_at'] = date( 'Y-m-d H:i:s' );

        DB::table( 'cr_agent' )->insert( $param );

        return $this->success();
    }

    public function read() {
        $result = DB::table( 'cr_agent' )->get();
        return $this->success($result);
    }
}
