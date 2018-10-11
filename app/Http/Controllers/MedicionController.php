<?php

namespace App\Http\Controllers;

use App\Cultivo;
use Illuminate\Http\Request;

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
        
        $dispositivo = \App\Dispositivo::where(['codigo' => $datos['chipID']])->first();
        
        $cultivo = $dispositivo->cultivoActual;
        
        //$faseCultivo = $cultivo->
        
        $medicion = new Medicion($datos);
        
        if($cultivo->mediciones()->save($medicion)){
            return $medicion->id;
        }
        return $medicion->errors();
    }
    
    public function test(){
        $cultivo = Cultivo::find(1);
        
        return $cultivo->faseActual;
    }
    
}
