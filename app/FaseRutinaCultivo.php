<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FaseRutinaCultivo extends Model
{
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fases_rutina_cultivo';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['duracion', 'horas_luz', 'fase_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
    
    public $timestamps = false;
    
    public static $rules = [
        'duracion' => 'required|numeric|between:1,300',
        'horas_luz' => 'required|numeric|between:0,24',
        'fase_id' => 'exists:fases,id'
    ];
    
    public function rutinaCultivo(){
        return $this->belongsTo('App\RutinaCultivo');
    }
    
    public function fase(){
        return $this->belongsTo('App\Fase', 'fase_id');
    }
    
    public function parametrosFaseCultivo(){
        return $this->hasMany('App\ParametroFaseCultivo');
    }
    
}
