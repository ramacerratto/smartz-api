<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

    const CREATED_AT = 'fecha_alta';
    const UPDATED_AT = 'fecha_modificacion';
    
    const ACTIVO = 1;
    const INACTIVO = 0;
    
    protected $dates = ['fecha_alta', 'fecha_modificacion', 'fecha_baja'];
    
    protected static $rules = array();

}
