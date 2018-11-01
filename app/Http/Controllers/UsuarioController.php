<?php

namespace App\Http\Controllers;

use App\Usuario;

class UsuarioController extends Controller
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

    public function crear(Request $request){
        $this->validate($request, Usuario::$rules);
        
        $datos = $request->all();
        
        $usuario = Usuario::updateOrCreate($datos);
        
        return response()->json($usuario, 201);
    }
}
