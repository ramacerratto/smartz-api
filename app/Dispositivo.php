<?php

namespace App;

use App\BaseModel;

class Dispositivo extends BaseModel
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dispositivos';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['codigo', 'descripcion'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
    
    protected $attributes = [
        'estado' => parent::ACTIVO
    ];

    public $rules = [
        'codigo' => 'required|unique:dispositivos',
        'descripcion' => 'required|alpha_dash|max:255'
    ];

    /**
     * Obtiene los cultivos para el dispositivo.
     */
    public function cultivos()
    {
        return $this->hasMany('App\Cultivo','dispositivos_id');
    }
    
    public function cultivoActual()
    {
        return self::with(['cultivos' => function ($query) {
            $query->where('estado', parent::ACTIVO);
        }])->get();
    }
    
}
