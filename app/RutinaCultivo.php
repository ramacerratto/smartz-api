<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RutinaCultivo extends Model
{
    
    const CREATED_AT = 'fecha_alta';
    const UPDATED_AT = 'fecha_modificacion';
    
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
    protected $fillable = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
    
    protected $dates = ['fecha_alta', 'fecha_modificacion', 'fecha_baja'];

    public static $rules = [
            // Validation rules
    ];
    
    public function fasesRutinaCultivo()
    {
        return $this->hasMany('App\FaseRutinaCultivo', 'rutinas_cultivo_id');
    }
    
}
