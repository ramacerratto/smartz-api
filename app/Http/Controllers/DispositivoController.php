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
    public function index(Request $request, $codUsuario){
        $dispositivos = Dispositivo::whereHas('usuarios', function($query) use ($codUsuario){
            $query->where('codigo',$codUsuario);
        })->get();
        return response()->json($dispositivos, 200);
    }

    /**
     * 
     * @param Request $request
     */
    public function crear(Request $request){
        $this->validate($request, Dispositivo::$rules);
        
        $datos = $request->all();
        
        $fechaHoy = new \DateTime();
        $tiempoCambioFiltro = config('parametros.config.tiempo_cambio_filtro');
        $datos['fecha_cambio_filtro'] = $fechaHoy->add(new \DateInterval("P{$tiempoCambioFiltro}M"))->format('d/m/Y');
        
        $dispositivo = Dispositivo::firstOrCreate(['codigo' => $datos['codigo']],$datos);
        
        $usuario = \App\Usuario::firstOrCreate(['codigo' => $datos['usuario_id']],$datos);
        
        $usuario->dispositivos()->syncWithoutDetaching($dispositivo);

        return response()->json($dispositivo, 201);
    }
    
    public function get(Request $request, $id){
        $dispositivo = Dispositivo::where('id',$id)->with(['cultivos' => function ($query){
                $query->where('estado', '!=', \App\Cultivo::INACTIVO);
        }])->first();
        return response()->json($dispositivo, 200);
    }
    
    public function editar(Request $request, $id) {
        $this->validate($request, [
            'hora_inicio' => 'date_format:H',
            'notificaciones_on' => 'boolean',
            'fecha_cambio_filtro' => 'date_format:"d/m/Y"',
            'luz_on' => 'boolean',
            'vaciar' => 'boolean'
        ]);
        
        $datos = $request->all();
        
        $dispositivo = Dispositivo::findOrFail($id);
        
        $dispositivo->update($datos);
        
        return response()->json($dispositivo, 200);
    }
    
    /**
     * Método para prender o apagar el dispositivo
     * 
     * @param Request $request
     * @param type $id
     * @return boolean
     */
    public function trigger(Request $request, $id){
        $this->validate($request, [
            'on' => 'required|boolean' 
        ]);
        
        $dispositivo = Dispositivo::findOrFail($id);
        if(!$dispositivo->cultivoActual()){
            return response()->json('No hay cultivo activo', 403);
        }
        
        $dispositivo->estado = ($request->input('on'))?Dispositivo::ON:Dispositivo::OFF;
        $dispositivo->save();
        
        return response()->json($dispositivo, 200);
    }
    
    /**
     * Método para prender o apagar el dispositivo
     * 
     * @param Request $request
     * @param type $id
     * @return boolean
     */
    public function vaciar(Request $request, $id){
        $dispositivo = Dispositivo::findOrFail($id);
        if(!$dispositivo->cultivoActual()){
            return response()->json('No hay cultivo activo', 403);
        }
        
        $dispositivo->vaciar = 1;
        $dispositivo->save();
        
        return response()->json($dispositivo, 200);
    }
}
