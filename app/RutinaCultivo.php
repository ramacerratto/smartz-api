<?php

namespace App;

class RutinaCultivo extends BaseModel
{
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rutinas_cultivo';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'descripcion'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['dias_totales'];
    
    protected $attributes = [
        'estado' => parent::ACTIVO,
    ];
    
    public static $rules = [
        'nombre' => 'required|alpha',
        'descripcion' => 'required'
    ];
    
    public function fasesRutinaCultivo()
    {
        return $this->hasMany('App\FaseRutinaCultivo', 'rutina_cultivo_id');
    }
    
    public function getDiasTotalesAttribute(){
        $diasTotales = 0;
        foreach($this->fasesRutinaCultivo as $fase){
            $diasTotales += $fase->duracion;
        }
        return $diasTotales;
    }
}
