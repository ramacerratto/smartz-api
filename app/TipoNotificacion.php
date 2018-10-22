<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoNotificacion extends Model
{
    const ALERTA = 0;
    const ERROR = 1;
    
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
