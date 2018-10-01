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

    public $rules = [
        'dispositivos_id' => 'required|exists:dispositivos,id',
        'rutinas_cultivo_id' => 'required|exists:rutinas_cultivo,id',
    ];

    /**
     * Obtiene la rutina de cultivo del cultivo
     */
    public function rutinaCultivo()
    {
        return $this->belongsTo('App\RutinaCultivo');
    }
}
