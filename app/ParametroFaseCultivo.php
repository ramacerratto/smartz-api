<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParametroFaseCultivo extends Model
{
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'parametros_fase_cultivo';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['valor_esperado', 'descripcion', 'parametro_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
    
    protected $dates = [];
    
    public static $rules = [
        'valor_esperado' => 'required|float',
        'descripcion' => 'required', //-> es el nombre que se le devuelve al arduino (pHaceptable)
        'parametro_id' => 'exists:parametros,id' 
    ];

    public $timestamps = false;
    
    /**
     * Obtiene la rutina de cultivo del cultivo
     */
    public function rutinaCultivo()
    {
        return $this->belongsTo('App\RutinaCultivo');
    }
}
