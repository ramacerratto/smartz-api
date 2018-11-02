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
     
    public function index(Request $request, $idUsuario){
        $dispositivos = \App\UsuarioDispositivo::findDispositivos($idUsuario);
        return response()->json($dispositivos, 200);
    }*/

    /**
     * Obtiene los dispositivos de un usuario
     * 
     * @param Request $request
     * @param int $idUsuario
     * @return array
     */
    public function index(Request $request, $codUsuario){
        $usuario = \App\Usuario::where('codigo',$codUsuario)->with('dispositivos')->first();
        return response()->json($usuario->dispositivos, 200);
    }

    /**
     * 
     * @param Request $request
     */
    public function crear(Request $request){
        $this->validate($request, Dispositivo::$rules);
        
        $datos = $request->all();
        
        $dispositivo = Dispositivo::firstOrCreate(['codigo' => $datos['codigo']],$datos);
        
        $usuario = \App\Usuario::firstOrCreate(['codigo' => $datos['usuario_id']]);
        
        $usuario->dispositivos()->syncWithoutDetaching($dispositivo);

        return response()->json($dispositivo, 201);
    }
    
    public function get(Request $request, $id){
        $dispositivo = Dispositivo::where('id',$id)->with(['cultivos' => function ($query){
            $query->where('estado', \App\Cultivo::ACTIVO);
        }])->first();
        return response()->json($dispositivo, 200);
    }
    
    public function editar(Request $request, $id) {
        $this->validate($request, [
            'hora_inicio' => 'date_format:H',
            'notificaciones_on' => 'boolean',
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
