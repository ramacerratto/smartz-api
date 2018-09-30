<?php

namespace App\Http\Controllers;

use App\Cultivo;
use Illuminate\Http\Request;

class CultivoController extends Controller
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
     * Obtiene los cultivos para un dispositivo
     * 
     * @param Request $request
     * @param int $idDispositivo
     * @return array
     */
    public function get(Request $request, $idDispositivo){
        
        $dispositivo = \App\Dispositivo::find($idDispositivo);
        return $dispositivo->cultivos;
    }

    /**
     * 
     * @param Request $request
     */
    public function crear(Request $request){
        $datos = $request->all();
        
        $dispositivo = \App\Dispositivo::find($datos['dispositivos_id']);
        
        $cultivo = new Cultivo($datos);
        
        if($dispositivo->cultivos()->save($cultivo)){
            return $cultivo->id;
        }
        return false;
    }
    
}
