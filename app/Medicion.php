<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medicion extends Model
{

    const CREATED_AT = 'fecha';
    
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
    protected $fillable = ['dispositivos_id', 'rutinas_cultivo_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
    
    protected $dates = ['fecha_inicio'];
    
    protected $attributes = [
        'estado' => self::ACTIVO
    ];

    public static $rules = [
        'dispositivos_id' => 'required|exists:dispositivos,id',
        'rutinas_cultivo_id' => 'required|exists:rutinas_cultivo,id',
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
        return $this->belongsTo('App\Parametro');
    }
}
