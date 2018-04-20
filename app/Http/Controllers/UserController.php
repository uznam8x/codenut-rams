<?php

namespace App\Http\Controllers;

use App\Helper\Action;
use App\Helper\Addon;
use App\Helper\Data;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use App\Http\Generator\Response;
use Mockery\Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller {
    use Response;

    protected $table = 'cr_users';

    public function create(Request $request) {

        $param = $request->only( ['email', 'password'] );
        $validator = Validator::make( $param, ['email' => 'required|string|email|max:255|unique:cr_users', 'password' => 'required|string|min:6'] );

        if ($validator->fails()) {
            return $this->error( $validator->errors() );
        }
        $param['password'] = bcrypt( $param['password'] );
        $param['created_at'] = date( 'Y-m-d H:i:s' );
        $param['updated_at'] = date( 'Y-m-d H:i:s' );

        DB::table( $this->table )->insert( $param );

        return $this->login( $request );
    }

    public function read(Request $request) {
        $user = JWTAuth::toUser( $request->token );
        $validator = Validator::make( array('token' => $request->token), ['token' => 'required'] );
        if ($validator->fails()) {
            return $this->error( $validator->errors() );
        }
        return $this->success( $user );
    }

    public function login(Request $request) {
        $credentials = $request->only( 'email', 'password' );
        $validator = Validator::make( $credentials, ['email' => 'required|string|email', 'password' => 'required|string|min:6'] );
        if ($validator->fails()) {
            return $this->error( $validator->errors() );
        }

        $token = null;
        try {
            if (!$token = JWTAuth::attempt( $credentials )) {
                return $this->error( array('message' => 'invalid email or password') );
            }
        } catch (JWTAuthException $e) {
            return $this->error( array('message' => 'failed to create token') );
        }

        return $this->success( array('token' => $token) );
    }

    public function logout(Request $request) {
        $param = $request->only( 'token' );
        $validator = Validator::make( $param, ['token' => 'required'] );
        if ($validator->fails()) {
            return $this->error( $validator->errors() );
        }
        try {
            if (JWTAuth::invalidate( $param['token'] )) {
                return $this->success();
            } else {
                return $this->error( array('message' => 'error') );
            }

        } catch (JWTException $e) {
            return $this->error( array('message' => $e->getMessage()) );
        }

    }
}