<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    const CREATED_AT = 'fecha_alta';
    const UPDATED_AT = 'fecha_modificacion';
    
    const PENDIENTE = 0;
    const ENVIADA = 1;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notificaciones';
    
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
    
    protected $dates = ['fecha_alta', 'fecha_modificacion'];
    
    protected $attributes = [
        'estado' => self::PENDIENTE
    ];
    
    public function tipoNotificacion(){
        return $this->belongsTo('App\TipoNotificacion');
    }
    
    public function cultivo(){
        return $this->belongsTo('App\Cultivo');
    }
    
    public function dispositivo(){
        return $this->belongsTo('App\Dispositivo');
    }
    
}
