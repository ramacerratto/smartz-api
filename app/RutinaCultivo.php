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
    protected $fillable = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
    
    public static $rules = [
            // Validation rules
    ];
    
    public function fasesRutinaCultivo()
    {
        return $this->hasMany('App\FaseRutinaCultivo');
    }
    
}
