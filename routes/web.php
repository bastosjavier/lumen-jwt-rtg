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

$app->group([
    'namespace' => '\App\Http\Controllers\V1',
    'prefix' => 'api/v1'], 
    function () use ($app) {
        $app->post('authenticate', 'AuthenticationController@authenticate');
});

$app->group([
    'prefix' => 'api/v1', 
    'namespace' => '\App\Http\Controllers\V1',
    'middleware' => ['before' => 'jwt-auth']], 
    function () use ($app) {
        $app->get('test', 'AuthenticationController@sayHello');
});
