<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsuarioDispositivo extends Model
{
    public $timestamps = false;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'usuarios_dispositivos';
    
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
    
    public static function findDispositivos($idUsuario)
    {
        $array = [];
        $results = self::where('usuario_id',$idUsuario)->with('dispositivo')->get();
        foreach($results as $result){
            $array[] = $result->dispositivo;
        }
        return $array;
    }
    
    public function dispositivo(){
        return $this->belongsTo('App\Dispositivo');
    }
}
