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
        $mediciones = Medicion::where('cultivo_id',$id)->where('fecha', function($query) use ($id){
                $query->selectRaw('MAX(fecha) as fecha')->from('mediciones')->where('cultivo_id',$id)->first();
            })->with([
                'parametro' => function ($query){
                    $query->select('id','nombre');
                },
                'faseRutinaCultivo.parametrosFaseCultivo' => function($query){
                    $query->select('fase_rutina_cultivo_id','valor_esperado');
                }
        ])->groupBy('parametro_id')->get();
        return response()->json(['mediciones' => $mediciones->keyBy('parametro.nombre')->all()],200);
    }
    
    /**
     * 
     * @param Request $request
     */
    public function registrar(Request $request){
        
        $datos = json_decode($request->input('dato'), true);
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
        $respuesta['Power'] = ($dispositivo->estado == Dispositivo::ON || $dispositivo->estado == Dispositivo::SIN_CONEXION)?1:0; //Envía estado del dispositivo (ON/OFF)
        $respuesta['Vaciado'] = $dispositivo->vaciar();
        
        $dispositivo->estado = $respuesta['Power'];
        $dispositivo->save();
        
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
    
    /**
     * Reporte lineal de mediciones en el tiempo
     * 
     * @param Request $request
     * @param int $idCultivo
     * @param int $idParametro
     * @return array [labels, valores]
     */
    public function reporte(Request $request,$idCultivo,$idParametro,$tiempo = null){
        $tiempo = ($tiempo)?$tiempo:1;
        $hoy = new \DateTime();
        $fechaLimite = $hoy->sub(new \DateInterval("P{$tiempo}W"))->format('Y-m-d H:i:s');
        $mediciones = Medicion::where(['cultivo_id' => $idCultivo, 'parametro_id' => $idParametro,['fecha','>=',$fechaLimite] ])
                ->select('fecha','valor')
                ->selectRaw('dayofyear(fecha) Day, date_format(fecha,"%d/%m/%Y") as fecha_sh')
                ->groupBy('Day')
                ->orderBy('fecha','desc')
                ->get();
        
        $result = [
            $mediciones->pluck('fecha_sh'),
            $mediciones->pluck('valor'),
        ];
        return $result;
    }
    
    public function test(){
        $cultivo = Cultivo::find(2);
        
        return $cultivo->faseActual();
    }
    
}
