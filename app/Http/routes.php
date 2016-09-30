<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->version();
});

/**
 * api v1 routes group
 */
$app->group(['prefix'=>'api/v1','namespace'=>'App\Http\Controllers'],function() use($app){
    /**
     * CRUD client routes
     */
    $app->get('client','ClientController@index');
    $app->get('client/{id}','ClientController@get');
    $app->post('client','ClientController@create');
    $app->put('client/{id}','ClientController@update');
    $app->delete('client/{id}','ClientController@delete');
});