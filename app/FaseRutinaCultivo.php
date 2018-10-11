<?php

namespace App;

use App\BaseModel;

class FaseRutinaCultivo extends BaseModel
{
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fases_rutina_cultivo';
    
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
    
    public function fase(){
        return $this->belongsTo('App\Fase', 'fases_id');
    }
    
}
