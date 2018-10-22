<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cultivo;
use App\Dispositivo;
use App\Parametro;
use App\Medicion;

class MedicionController extends Controller
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
     * 
     * @param Request $request
     */
    public function registrar(Request $request){
        
        $datos = $request->all();
        $respuesta = [];
        
        //Obtengo datos maestros
        $dispositivo = Dispositivo::where(['codigo' => $datos['chipID']])->firstOrFail();
        $cultivo = $dispositivo->cultivoActual();
        $faseCultivo = $cultivo->faseActual();
        
        //Armo array de respuesta:
        $respuesta['id'] = $datos['chipID'];
        $respuesta['HorasLuz'] = $faseCultivo->horas_luz;
        $respuesta['HoraInicioLuz'] = $dispositivo->hora_inicio;
        $respuesta['Power'] = ($dispositivo->estado == Dispositivo::ON)?1:0; //Envía estado del dispositivo (ON/OFF)
        $respuesta['Vaciado'] = $dispositivo->vaciar();
        
        foreach($datos as $key => $dato){
            $parametro = Parametro::whereDescripcion($key)->get()->first();
            if($parametro){
                $medicion = new Medicion();
                $medicion->parametro()->associate($parametro);
                $medicion->faseRutinaCultivo()->associate($faseCultivo);
                $medicion->valor = $dato;
                $medicion->fecha = new \DateTime;
                
                $cultivo->mediciones()->save($medicion);
                
                if($parametroFaseCultivo = $parametro->parametroFaseCultivo($faseCultivo)){
                    $respuesta[$parametroFaseCultivo->descripcion] = $parametroFaseCultivo->valor_esperado;
                }
            }
        }
        
        return response()->json($respuesta, 200);
    }
    
    public function test(){
        $cultivo = Cultivo::find(1);
        
        return $cultivo->faseActual();
    }
    
}
