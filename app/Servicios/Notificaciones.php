<?php
namespace App\Servicios;

/**
 * Servicio para envio de notificaciones
 *
 * @author RAMA
 */
class Notificaciones {
    
    private $headers;
    private $contenido = [
        'registration_ids' => [],
	'notification' => [
            'title' => '',
            'body' => '',
        ]
    ];
    
    public function __construct() {
        $this->headers = [
            'Authorization: key=' . config('api.access.key'),
            'Content-Type: application/json'
        ];
    }
    
    public function enviar($notificacion){
        $this->contenido['notification']['title'] = $notificacion->tipoNotificacion->titulo;
        $this->contenido['notification']['body'] = $notificacion->tipoNotificacion->mensaje;
        $this->contenido['registration_ids'] = \App\Usuario::with('dispositivos')
            ->whereHas('dispositivos', function($q) use ($notificacion){
                $q->where('id',$notificacion->dispositivo_id);
            })->pluck('device_token')->toArray();
        
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, config('api.access.url') );
        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $this->headers );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $this->contenido ) );
        $result = curl_exec($ch );
        curl_close( $ch );
        
        return $result;
    }

}
