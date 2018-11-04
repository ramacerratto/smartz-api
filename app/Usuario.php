<?php

namespace App;

use App\BaseModel;

class Usuario extends BaseModel
{
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'usuarios';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['codigo', 'device_token', 'email'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
    
    public static $rules = [
        'codigo' => 'required|alpha_num',
        'device_token' => 'required|alpha_num',
    ];
    
    public function dispositivos(){
        return $this->belongsToMany('App\Dispositivo', 'usuarios_dispositivos');
    }
    
    public function rutinasCultivo(){
        return $this->hasMany('App\RutinaCultivo');
    }
}
