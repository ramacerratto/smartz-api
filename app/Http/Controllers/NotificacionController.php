<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TipoNotificacion;
use App\Notificacion;
use App\Servicios\Notificaciones;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacionMailer;

class NotificacionController extends Controller
{
    
    /**
     * Notificaciones service.
     *
     * @var Notificaciones
     */
    protected $notificaciones;
    
    public function __construct() {
    }
    
    public function get(Request $request, $id, $enviadas){
        $condiciones = ['id' => $id];
        $estado = Notificacion::ENVIADA;
        if(!$enviadas){
            $condiciones['notificaciones_on'] = 1;
            $estado = Notificacion::PENDIENTE;
        }
        $notificaciones = Notificacion::where('estado', $estado)->whereHas('dispositivo', function($query) use ($id, $condiciones) {
            $query->where($condiciones);
        })->with('tipoNotificacion');
        $result = $notificaciones->orderBy('fecha_alta', 'desc')->get();
        $notificaciones->update(['estado' => Notificacion::ENVIADA]);
        return response()->json(['notificaciones' => $result->take(10)],200);
    }
    
    /**
     * 
     * @param Request $request
     */
    public function registrar(Request $request){
        $this->validate($request, Notificacion::$rules);
        
        $datos = json_decode($request->input('dato'), true);
        
        //Obtengo datos maestros
        $dispositivo = \App\Dispositivo::where(['codigo' => $datos['chipID']])->firstOrFail();
        $tipos = [
            TipoNotificacion::ALERTA => $datos['Alertas'],
            TipoNotificacion::ERROR => $datos['Errores']
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
                    //Mail::to($dispositivo->getEmails())->send(new NotificacionMailer($notificacion));
                }
            }
        }
        return true;
    }  
    
}
