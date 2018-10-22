<?php

namespace App;

use App\BaseModel;

class Cultivo extends BaseModel
{

    const CREATED_AT = 'fecha_inicio';
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cultivos';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['dispositivo_id', 'rutina_cultivo_id'];

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
        'dispositivo_id' => 'required|exists:dispositivos,id',
        'rutina_cultivo_id' => 'required|exists:rutinas_cultivo,id',
    ];
    
    /**
     * Obtiene la rutina de cultivo del cultivo
     */
    public function rutinaCultivo()
    {
        return $this->belongsTo('App\RutinaCultivo', 'rutina_cultivo_id');
    }
    
    /**
     * Obtiene las mediciones del cultivo 
     */
    public function mediciones()
    {
        return $this->hasMany('App\Medicion');
    }
    
    /**
     * Devuelve la fase actual o false si el ciclo ya termino
     * 
     * @return boolean
     */
    public function faseActual(){
        $fechaInicio = new \DateTime($this->fecha_inicio);
        $interval = $fechaInicio->diff(new \DateTime());
        $transcurrido = $interval->format('%a');
        $rutinasCultivo = $this->belongsTo('App\RutinaCultivo','rutina_cultivo_id')->with(['fasesRutinaCultivo.fase' => function ($query) {
            $query->orderBy('orden', 'asc');
        }])->first();
        
        foreach($rutinasCultivo->fasesRutinaCultivo as $fase){
            $transcurrido -= $fase->duracion;
            if($transcurrido <= 0){
                return $fase;
            }
        }
        return false;
    }
}
