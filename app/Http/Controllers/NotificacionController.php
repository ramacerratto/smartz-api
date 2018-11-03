<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TipoNotificacion;
use App\Notificacion;
use App\Servicios\Notificaciones;

class NotificacionController extends Controller
{
    
    /**
     * Notificaciones service.
     *
     * @var Notificaciones
     */
    protected $notificaciones;
    
    public function __construct() {
        
        //$this->notificaciones = $notificaciones;
    }
    
    public function get(Request $request, $id){
        $dispositivo = \App\Dispositivo::with('notificaciones.tipoNotificacion')->findOrFail($id);
        return response()->json(['notificaciones' => $dispositivo->notificaciones->take(10)],200);
    }
    
    /**
     * 
     * @param Request $request
     */
    public function registrar(Request $request){
        $this->validate($request, [
            'Alertas' => 'required|string', 
            'Errores' => 'required|string'
        ]);
        
        //Obtengo datos maestros
        $dispositivo = \App\Dispositivo::where(['codigo' => $request->input('chipID')])->firstOrFail();
        $tipos = [
            TipoNotificacion::ALERTA => $request->input('Alertas'),
            TipoNotificacion::ERROR => $request->input('Errores')
        ];
        foreach ($tipos as $tipo => $string) {
            for($i=0; $i < strlen($string); $i++){
                if($string[$i] == 1){
                    $tipoNotificacion = TipoNotificacion::where([
                        'tipo' => $tipo,
                        'pos_string' => $i
                    ])->firstOrFail();
                    $notificacion = new Notificacion();
                    $notificacion->tipoNotificacion()->associate($tipoNotificacion);
                    $dispositivo->notificaciones()->save($notificacion);
                    
                    //$this->notificaciones->enviar($notificacion);
                }
            }
        }
        return true;
    }  
    
}
