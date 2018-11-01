<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoNotificacion extends Model
{
    //Tipo de Notificación
    const ALERTA = 0;
    const ERROR = 1;
    
    //Posición 
    const SINCONEXION = 12;
    const CAMBIOFASE = 8;
    const COSECHA = 9;
    const VACIADO = 10;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tipos_notificacion';
    
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
    
}
