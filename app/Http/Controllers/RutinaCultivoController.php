<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RutinaCultivo;

class RutinaCultivoController extends Controller
{

    public function index($codUsuario = null){
        $rutinasCultivo = RutinaCultivo::whereHas('usuario',function ($query) use ($codUsuario){
            $query->where('codigo',$codUsuario);
        })->orDoesntHave('usuario')->get();
        
        return response()->json(['rutinas_cultivo' => $rutinasCultivo ],200);
    }
    
    /**
     * 
     * @param Request $request
     */
    public function crear(Request $request){
        $this->validate($request, RutinaCultivo::$rules);
        
        $datos = $request->all();
        
        $usuario = \App\Usuario::where('codigo',$datos['codigo_usuario'])->firstOrFail();
        
        $datosRutina = json_decode($datos['rutina_cultivo'],true);
        
        $rutinaCultivo = new RutinaCultivo($datosRutina);
        
        $usuario->rutinasCultivo()->save($rutinaCultivo);
        
        foreach ($datosRutina['fasesRutinaCultivo'] as $datosFaseRutinaCultivo){
            //Creo las fasesRutinaCultivo
            $faseRutinaCultivo = $rutinaCultivo->fasesRutinaCultivo()->create($datosFaseRutinaCultivo);
            
            //Creo los parametrosFaseCultivo para la fasesRutinaCultivo
            $faseRutinaCultivo->parametrosFaseCultivo()->createMany($datosFaseRutinaCultivo['parametrosFaseCultivo']);
        }
        
        $rutinaCultivo->save();

        return response()->json($rutinaCultivo, 201);
    }
}
