<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function () use ($router) {
  //  $value = config('app.name');
  //$value=env("APP_NAME");
    
   return $router->app->version();
  
   // echo "hello";
});

$router->post('/save', 'ExampleController@save');
$router->group( ['middleware' => 'auth'], function($router) {
	$router->get('/user/profile', function () {
        echo 'Nancy12345';
    });

});
$router->group( ['middleware' => 'auth:mgt_api'], function() use ($router) {
    $router->get('/test', 'ExampleController@test');
    $router->get('/userinfo', 'ExampleController@userinfo');
    $router->post('/cOrder0317', 'ExampleController@cOrder0317');
    $router->post('/create_order', 'ExampleController@create_order');
    $router->post('/qiantao0318', 'ExampleController@qiantao0318');
    $router->post('/DQtao0318', 'ExampleController@DQtao0318');
    $router->post('/DQtao0318_1', 'ExampleController@DQtao0318_1');
    $router->get('/tms', function () {
      //  return 'hello google0208';
      return response()->json(['name' => 'tony', 'state' => 'CA']);
    });

});

