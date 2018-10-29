<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\RutinaCultivo;

class RutinaCultivoController extends Controller
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

    public function index(){
        return response()->json(['rutinas_cultivo' => RutinaCultivo::all()],200);
    }
    
    /**
     * 
     * @param Request $request
     */
    public function crear(Request $request){
        //$this->validate($request, RutinaCultivo::$rules);
        
        //$datos = $request->all();
        
        $datos = [
            'nombre' => 'Nueva Rutina',
            'descripcion' => 'Nueva rutina',
            'fasesRutinaCultivo' => [
                [
                    'duracion' => 47,
                    'horas_luz' => 10,
                    'fase_id' => 1, //1: vegetativo
                    'parametrosFaseCultivo' => [
                        [
                            'valor_esperado' => 15,
                            'descripcion' => 'pHaceptable', //-> es el nombre que se le devuelve al arduino (pHaceptable)
                            'parametro_id' => 1 
                        ]
                    ]
                ],
                [
                    'duracion' => 30,
                    'horas_luz' => 15,
                    'fase_id' => 2, //2: floracion
                    'parametrosFaseCultivo' => [
                        [
                            'valor_esperado' => 15,
                            'descripcion' => 'pHaceptable', //-> es el nombre que se le devuelve al arduino (pHaceptable)
                            'parametro_id' => 1 
                        ]
                    ]
                ],
            ]
        ];
        
        $rutinaCultivo = new RutinaCultivo($datos);
        
        $rutinaCultivo->save();
        foreach ($datos['fasesRutinaCultivo'] as $datosFaseRutinaCultivo){
            //TODO: Faltan validaciones $this->validate($request, \App\FaseRutinaCultivo::$rules);
            //Creo las fasesRutinaCultivo
            $faseRutinaCultivo = $rutinaCultivo->fasesRutinaCultivo()->create($datosFaseRutinaCultivo);
            
            //Creo los parametrosFaseCultivo para la fasesRutinaCultivo
            $faseRutinaCultivo->parametrosFaseCultivo()->createMany($datosFaseRutinaCultivo['parametrosFaseCultivo']);
        }
        
        $rutinaCultivo->save();

        return response()->json($rutinaCultivo, 201);
    }
}
