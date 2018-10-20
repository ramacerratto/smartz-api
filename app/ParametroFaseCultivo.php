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
    protected $fillable = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
    
    protected $dates = [];
    
    public static $rules = [];

    /**
     * Obtiene la rutina de cultivo del cultivo
     */
    public function rutinaCultivo()
    {
        return $this->belongsTo('App\RutinaCultivo');
    }
}
