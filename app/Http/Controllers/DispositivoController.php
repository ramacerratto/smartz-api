<?php

namespace App\Http\Controllers;

use App\Dispositivo;
use Illuminate\Http\Request;

class DispositivoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    
    /**
     * Obtiene los dispositivos de un usuario
     * 
     * @param Request $request
     * @param int $idUsuario
     * @return array
     */
    public function get(Request $request, $idUsuario){
        
        $dispositivos = \App\UsuarioDispositivo::findDispositivos($idUsuario);
        return $dispositivos;
    }

    /**
     * 
     * @param Request $request
     */
    public function crear(Request $request){
        $datos = $request->all();
        
        $dispositivo = new Dispositivo($datos);
        
        if($dispositivo->save()){
            $usuarioDispositivo = new \App\UsuarioDispositivo();
            $usuarioDispositivo->usuarios_id = $datos['usuarios_id'];
            $usuarioDispositivo->dispositivos_id = $dispositivo->id;
            if($usuarioDispositivo->save()){
                return $usuarioDispositivo->id;
            }
        }
        return false;
    }
    
}
