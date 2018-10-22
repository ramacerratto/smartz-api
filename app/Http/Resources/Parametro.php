<?php
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
            $this->descripcion  => $this->valor_esperado
        ];
    }
}
