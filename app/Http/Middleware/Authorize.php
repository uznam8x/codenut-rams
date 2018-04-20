<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Exception;

class Authorize {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$permission) {
        $role = 'member';
        foreach ($permission as $level) {
            $role = $level;
        }

        $param = $request->input( 'token' );
        $validator = Validator::make( array('token' => $param), array('token' => 'required') );
        if ($validator->fails()) {
            return response()->json( ['code' => 500, 'data' => array('message' => $validator->errors())] );
        }
        try {
            $user = JWTAuth::toUser( $param );
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json( ['code' => 500, 'data' => array('message' => 'Token is Invalid')] );
            } else {
                if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                    return response()->json( ['code' => 500, 'data' => array('message' => 'Token is Expired')] );
                } else {
                    return response()->json( ['code' => 500, 'data' => array('message' => 'Something is wrong')] );
                }
            }
        }
        return $next( $request );
    }
}