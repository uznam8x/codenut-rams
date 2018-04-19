<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Agent {
    protected $table = 'cr_users';

    public function create(Request $request) {

        $param = $request->only(['email','password']);
        $validator = Validator::make($param, [
            'email' => 'required|string|max:255|unique:cr_users',
            'password' => 'required|string|min:6'
        ] );

        $param['password'] = bcrypt( $param['password'] );

        if ($validator->fails()) {
            return $this->error( $validator->errors() );
        }

        return $this->response( $this->insert($param) );
    }

    public function read(Request $request) {
        return $this->select();
    }
}