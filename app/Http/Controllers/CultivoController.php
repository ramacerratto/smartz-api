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
        $dispositivo = \App\Dispositivo::findOrFail($idDispositivo)->with(['cultivos.rutinaCultivo'])->first(); //Esto responde con excepción (404)
        return response()->json($dispositivo->cultivos,200);
    }

    /**
     * 
     * @param Request $request
     */
    public function crear(Request $request){
        $this->validate($request, Cultivo::$rules);
        
        $datos = $request->all();
        
        $dispositivo = \App\Dispositivo::findOrFail($datos['dispositivo_id']);
        
        if($dispositivo->cultivoActual()){
            return response()->json('El dispositivo ya tiene un cultivo activo.', 403);
        }
        
        $cultivo = new Cultivo($datos);
        
        $dispositivo->cultivos()->save($cultivo);
                
        $dispositivo->estado = \App\Dispositivo::ON; //"Prendo" el dispositivo
        $dispositivo->save();
        return response()->json($cultivo, 201);
    }
    
    //TODO: Finalizar CULTIVO
    
}
