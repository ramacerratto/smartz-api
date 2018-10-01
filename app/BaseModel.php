<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class BaseModel extends Model
{

    const CREATED_AT = 'fecha_alta';
    const UPDATED_AT = 'fecha_modificacion';
    
    const ACTIVO = 1;
    const INACTIVO = 0;
    
    protected $dates = ['fecha_alta', 'fecha_modificacion', 'fecha_baja'];
    
    protected $rules = array();

    protected $errors;

    public function save(array $options = [])
    {
        $data = $this->getAttributes();

        // make a new validator object
        $v = Validator::make($data, $this->rules);

        // check for failure
        if ($v->fails())
        {
            // set errors and return false
            $this->errors = $v->errors();
            return false;
        }

        // validation pass
        return parent::save($options);
    }

    public function errors()
    {
        return $this->errors;
    }
}
