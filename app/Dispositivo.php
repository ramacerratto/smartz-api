<?php

namespace App;

use App\BaseModel;

class Dispositivo extends BaseModel
{
    const OFF      = 0;
    const ON       = 1;
    const VACIANDO = 2;
    const LLENANDO = 3;
    const SIN_CONEXION = 4;
    
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
    protected $fillable = [
        'codigo', 
        'descripcion', 
        'hora_inicio', 
        'notificaciones_on', 
        'luz_on', 
        'vaciar',
        'estado'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
    
    protected $attributes = [
        'estado' => self::OFF,
    ];
    
    protected $dates = ['fecha_alta', 'fecha_modificacion', 'fecha_baja', 'fecha_vaciado'];

    public static $rules = [
        'codigo' => 'required|unique:dispositivos',
        'descripcion' => 'required|alpha_dash|max:255',
        'hora_inicio' => 'date_format:"H"',
        'usuario_id' => 'required|alpha_num'
    ];
    
    public function getHoraInicioAttribute(){
        return $this->hora_inicio->format('H');
    }

    /**
     * Obtiene los cultivos para el dispositivo.
     */
    public function cultivos()
    {
        return $this->hasMany('App\Cultivo');
    }
    
    public function cultivoActual()
    {
        return $this->hasMany('App\Cultivo')->where('estado', parent::ACTIVO)->first();
    }
    
    public function usuario(){
        return $this->hasMany('App\UsuarioDispositivo');
    }
    
    public function notificaciones(){
        return $this->hasMany('App\Notificacion')->orderBy('fecha_alta','DESC');
    }
    
    /**
     * Devuelve la condicion de vaciado segun dias transcurridos o 
     * directiva desde la App.
     * Si devuelve vaciado se actualiza la fecha de vaciado
     * 
     * @return int
     */
    public function vaciar(){
        $fechaVaciado = new \DateTime($this->fecha_vaciado);
        $interval = $fechaVaciado->diff(new \DateTime());
        $transcurrido = $interval->format('%a');
        
        if($this->vaciar == 1 || $transcurrido >= config('app.vaciado.dias') ){
            $this->fecha_vaciado = new \DateTime();
            $this->save();
            //TODO: Mandar notificación de que empieza vaciado.
            return 1;
        }
        return 0;
    }
    
}
