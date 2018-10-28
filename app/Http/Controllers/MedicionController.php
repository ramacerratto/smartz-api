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
    public function get(Request $request, $id){
        $mediciones = Medicion::where('cultivo_id',$id)->select('valor','fecha','parametro_id')->with([
            'parametro' => function ($query){
                $query->select('id','nombre');
            }
        ])->raw('MAX(fecha) as fecha')->groupBy('parametro_id')->get();
        
        return response()->json(['mediciones' => $mediciones->keyBy('parametro.nombre')->all()],200);
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
        if(!$cultivo = $dispositivo->cultivoActual()){
            return response()->json('No hay cultivo activo', 403);
        }
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
        
        //TODO: Pasar cultivo "en cosecha"
        
        return response()->json($respuesta, 200);
    }
    
    public function reporte(Request $request,$idCultivo,$idParametro){
        //TODO: REPORTE DE LINEA
        /*
         * Array labels
         * Array Valores
         */
        
    }
    
    public function test(){
        $cultivo = Cultivo::find(2);
        
        return $cultivo->faseActual();
    }
    
}
