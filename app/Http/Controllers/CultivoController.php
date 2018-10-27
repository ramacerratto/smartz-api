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
        $dispositivo = \App\Dispositivo::with(['cultivos' => function($query){
            $query->where('estado', Cultivo::ACTIVO)->with(['rutinaCultivo' => function($query){
                $query->select('id','nombre');
            }]);
        }])->findOrFail($idDispositivo); //Esto responde con excepción (404)
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
    
    public function editar(Request $request, $id) {
        $this->validate($request, [
            'estado' => 'boolean',
        ]);
        
        $datos = $request->all();
        
        $cultivo = Cultivo::findOrFail($id);
        
        $cultivo->update($datos);
        
        return response()->json($cultivo, 200);
    }
    
}
