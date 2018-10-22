<?php

use Illuminate\Http\Request;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\MedicionController;
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

//Rutinas Cultivo
$router->get('rutinas_cultivo', 'RutinaCultivoController@index');

//Cultivos
$router->get( 'cultivos/{idUsuario}', 'CultivoController@get');
$router->post('cultivos/crear',       'CultivoController@crear');

//Dispositivos
$router->get( 'dispositivos/{idUsuario}',    'DispositivoController@index');
$router->get( 'dispositivos/get/{id}',       'DispositivoController@get');
$router->post('dispositivos/crear',          'DispositivoController@crear');
$router->put( 'dispositivos/guardarAjustes/{id}', 'DispositivoController@guardarAjustes');
$router->put( 'dispositivos/trigger/{id}',   'DispositivoController@trigger');
$router->put( 'dispositivos/vaciar/{id}',    'DispositivoController@vaciar');

//Comunicacion Arduino
$router->post('comunicacionArduino', function(Request $request) use ($router) {
    $this->validate($request, [
        'chipID' => 'required|integer' 
    ]);
    
    (new NotificacionController)->registrar($request);
    
    return (new MedicionController)->registrar($request);
});

//Testing
$router->get('mediciones/test', 'MedicionController@test');
    