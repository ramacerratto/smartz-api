<?php

namespace App\Http\Controllers;

use App\Cultivo;
use Illuminate\Http\Request;
use App\Http\Resources\Parametro as ParametroResource;

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
        $this->validate($request, [
           'chipID' => 'required|integer' 
        ]);
        
        $datos = $request->all();
        $respuesta = [];
        
        $dispositivo = \App\Dispositivo::where(['codigo' => $datos['chipID']])->first();
        $cultivo = $dispositivo->cultivoActual;
        $faseCultivo = $cultivo->faseActual;
        
        $respuesta['CantidadHorasLuz'] = $faseCultivo->horas_luz;
        //$respuesta['HoraInicioLuz'] = obtener de conf;
        
        foreach($datos as $key => $dato){
            $parametro = App\Parametro::whereDescripcion($key)->get()->first();
            if($parametro){
                $medicion = new Medicion();
                $medicion->cultivo()->associate($cultivo);
                $medicion->parametro()->associate($parametro);
                $medicion->faseRutinaCultivo()->associate($faseCultivo);
                $medicion->valor = $dato;
                
                if(!$cultivo->mediciones()->save($medicion)){
                    return $medicion->errors();
                }
                $respuesta += new ParametroResource($parametro->parametroFaseCultivo($faseCultivo));
                
            }
        }
        
        return $respuesta;
    }
    
    public function test(){
        $cultivo = Cultivo::find(1);
        
        return $cultivo->faseActual();
    }
    
}
