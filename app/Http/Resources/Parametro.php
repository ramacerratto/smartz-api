<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Description of Parametro
 *
 * @author RAMA
 */
class Parametro extends JsonResource{
    
     /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            $this->parametro->descripcion . 'Min' => $this->valor_min,
            $this->parametro->descripcion . 'Max' => $this->valor_max,
        ];
    }
}
