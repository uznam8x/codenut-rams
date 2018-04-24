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
    Route::post( '/user', 'UserController@create' );
    Route::post( '/login', 'UserController@login' );
    Route::post( '/logout', 'UserController@logout' );

    Route::group( ['middleware' => 'authorize:super'], function() {
        Route::get( '/store', 'StoreController@read' );
        Route::post( '/store', 'AgentController@create' );
        Route::get( '/store/{name}', 'StoreController@read' );
        Route::post( '/store/{name}', 'StoreController@write' );
    } );
    Route::group( ['middleware' => 'authorize:staff'], function() {
        Route::get( '/user', 'UserController@read' );
        Route::get( '/addon', 'AddonController@read' );
        Route::post( '/addon/{name}', 'AddonController@create' );
        Route::delete( '/addon/{name}', 'AddonController@delete' );
    } );
    Route::group( ['middleware' => 'authorize:member'], function() {
        Route::patch( '/store/{name}/{xid}', 'StoreController@update' );
        Route::get( '/store/{name}/{xid}', 'StoreController@view' );
    } );

    Addon::routes();
} );
