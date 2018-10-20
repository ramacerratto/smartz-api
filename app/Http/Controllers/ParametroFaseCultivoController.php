<?php

namespace App\Http\Controllers;

use App\Cultivo;
use Illuminate\Http\Request;

class ParametroFaseCultivoController extends Controller
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
    public function get(Request $request){
        $this->validate($request, [
           'chipID' => 'required|integer' 
        ]);
        
        $datos = $request->all();
        
        $dispositivo = \App\Dispositivo::where(['codigo' => $datos['chipID']])->first();
        $cultivo = $dispositivo->cultivoActual;
        $faseCultivo = $cultivo->faseActual;
        
        $parametros = \App\ParametroFaseCultivo::where()
        
        return true;
    }
    
}
