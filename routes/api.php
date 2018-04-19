<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::middleware( 'auth:api' )->get( '/user', function(Request $request) {
    return $request->user();
} );
*/
Route::group( ['middleware' => 'mime:json'], function() {
    Route::get( '/user', 'UserController@read' );
    Route::post( '/user', 'UserController@create' );

    Route::get( '/agent', 'AgentController@read' );
    Route::post( '/agent', 'AgentController@create' );

    Route::get( '/store', 'StoreController@read' );
    Route::get( '/store/{name}', 'StoreController@read' );
    Route::get( '/store/{name}/{xid}', 'StoreController@view' );
    Route::post( '/store/{name}', 'StoreController@create' );


    Route::get( '/addon', 'AddonController@read' );
    Route::get( '/addon/{name}', 'AddonController@read' );
    Route::post( '/addon/{name}', 'AddonController@create' );
    Route::delete( '/addon/{name}', 'AddonController@delete' );

    //Route::post( '/addon/{name}', 'AddonController@activate' );
    //Route::delete( '/addon/{name}', 'AddonController@deactivate' );
} );
