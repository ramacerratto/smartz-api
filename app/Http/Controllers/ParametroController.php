<?php

namespace App\Http\Controllers;

use App\Parametro;
use Illuminate\Http\Request;

class ParametroController extends Controller
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
     * Obtiene los parametros
     * 
     * @param Request $request
     * @return array
     */
    public function index(){
        return response()->json(['parametros' => Parametro::all()],200);
    }

}
