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
        'rutina_cultivo.*.nombre' => 'required|alpha',
        'rutina_cultivo.*.descripcion' => 'required',
        'codigo_usuario' => 'required',
    ];
    
    public function fasesRutinaCultivo()
    {
        return $this->hasMany('App\FaseRutinaCultivo', 'rutina_cultivo_id');
    }
    
    public function usuario()
    {
        return $this->belongsTo('App\Usuario', 'usuario_id');
    }
    
    public function getDiasTotalesAttribute(){
        $diasTotales = 0;
        foreach($this->fasesRutinaCultivo as $fase){
            $diasTotales += $fase->duracion;
        }
        return $diasTotales;
    }
}
