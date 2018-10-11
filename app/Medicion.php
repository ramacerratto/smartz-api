<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medicion extends Model
{

    const CREATED_AT = 'fecha_inicio';
    const UPDATED_AT = false;
    
    const ACTIVO = 1;
    
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
     * Obtiene la rutina de cultivo del cultivo
     */
    public function rutinaCultivo()
    {
        return $this->belongsTo('App\RutinaCultivo');
    }

    /**
     * Obtiene el dispositivo del cultivo
     */
    public function dispositivo()
    {
        return $this->belongsTo('App\Dispositivo');
    }
}
