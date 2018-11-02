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
$router->get( 'rutinas_cultivo/get[/{codUsuario}]', 'RutinaCultivoController@index');
$router->post('rutinas_cultivo/crear', 'RutinaCultivoController@crear');

//Parametreos
$router->get('parametros/get', 'ParametroController@index');

//Cultivos
$router->get( 'cultivos/{idDispositivo}', 'CultivoController@get');
$router->post('cultivos/crear',           'CultivoController@crear');
$router->put( 'cultivos/editar/{id}',     'CultivoController@editar');

//Dispositivos
$router->get( 'dispositivos/{codUsuario}', 'DispositivoController@index');
$router->get( 'dispositivos/get/{id}',     'DispositivoController@get');
$router->post('dispositivos/crear',        'DispositivoController@crear');
$router->put( 'dispositivos/editar/{id}',  'DispositivoController@editar');
$router->put( 'dispositivos/trigger/{id}', 'DispositivoController@trigger');
$router->put( 'dispositivos/vaciar/{id}',  'DispositivoController@vaciar');

//Notificaciones
$router->get( 'notificaciones/get/{id}', 'NotificacionController@get');

//Comunicacion Arduino
$router->post('comunicacionArduino', function(Request $request) use ($router) {
    $this->validate($request, [
        'chipID' => 'required|alpha_num'
    ]);
    
    (new NotificacionController)->registrar($request);
    
    return (new MedicionController)->registrar($request);
});

//Mediciones
$router->get('mediciones/get/{id}', 'MedicionController@get');
$router->get('mediciones/reporte/{idCultivo}/{idParametro}/{tiempo}', 'MedicionController@reporte');

//Testing
$router->get('mediciones/test', 'MedicionController@test');
    