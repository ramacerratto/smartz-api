<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parametro extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'parametros';
    
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
     * Obtiene los Parametros Fase Cultivo para el Parametro
     */
    public function parametrosFaseCultivo()
    {
        return $this->hasMany('App\ParametroFaseCultivo');
    }

    /**
     * Obtiene el Parametro Fase Cultivo para el Parametro para una
     * fase en especial
     */
    public function parametroFaseCultivo($fase)
    {
        return $this->hasMany('App\ParametroFaseCultivo', 'parametro_id')->where(['fase_rutina_cultivo_id' => $fase->id])->first();
    }
}
