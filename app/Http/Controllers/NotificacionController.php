<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TipoNotificacion;
use App\Notificacion;

class NotificacionController extends Controller
{
    
    public function get(Request $request, $id){
        $dispositivo = \App\Dispositivo::findOrFail($id);
        return response()->json($dispositivo->notificaciones,200);
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
                    
                    $this->enviar($notificacion);
                }
            }
        }
        
    }
    
    public function enviar($notificacion){
        //TODO: Enviar notif a Firebase
        
        $notificacion->estado = \App\Notificacion::ENVIADA;
        $notificacion->save();
        
        return true;
    }
    
}
