<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medicion extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mediciones';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['valor'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
    
    public $timestamps = false;
    
    public static $rules = [
        'valor' => 'required|float',
        'fecha' => 'required|date'
    ];
    
    /**
     * Obtiene la FaseRutinaCultivo de la medicion
     */
    public function faseRutinaCultivo()
    {
        return $this->belongsTo('App\FaseRutinaCultivo');
    }

    /**
     * Obtiene el cultivo de la medicion
     */
    public function cultivo()
    {
        return $this->belongsTo('App\Cultivo');
    }

    /**
     * Obtiene el parametro de la medición
     */
    public function parametro()
    {
        return $this->belongsTo('App\Parametro', 'parametro_id');
    }
}
