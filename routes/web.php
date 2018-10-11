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

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->get('rutinas_cultivo', 'RutinaCultivoController@index');


$router->get('cultivos/{idUsuario}', 'CultivoController@get');

$router->get('mediciones/test', 'MedicionController@test');

$router->post('cultivos/crear', 'CultivoController@crear');


$router->get('dispositivos/{idUsuario}', 'DispositivoController@get');

$router->post('dispositivos/crear', 'DispositivoController@crear');