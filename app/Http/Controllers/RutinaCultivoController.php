<?php

namespace App\Http\Controllers;

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
        return RutinaCultivo::all();
    }
}
