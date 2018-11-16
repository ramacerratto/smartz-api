<?php

namespace App;

use App\BaseModel;
use App\TipoNotificacion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacionMailer;

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
        'fecha_cambio_filtro',
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
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
       'fecha_cambio_filtro' => 'date:d/m/Y',
    ];
   
    protected $dates = ['fecha_alta', 'fecha_modificacion', 'fecha_baja', 'fecha_vaciado', 'fecha_cambio_filtro'];

    public static $rules = [
        'codigo' => 'required',
        'descripcion' => 'required|alpha_dash|max:255',
        'hora_inicio' => 'date_format:"H"',
        'usuario_id' => 'required|alpha_num',
        'email' => 'required|email'
    ];
    
    public function setFechaCambioFiltroAttribute($value){
        $this->attributes['fecha_cambio_filtro'] = Carbon::createFromFormat("d/m/Y", $value );
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
    
    public function usuarios(){
        return $this->belongsToMany('App\Usuario', 'usuarios_dispositivos');
    }
    
    public function notificaciones(){
        return $this->hasMany('App\Notificacion')->orderBy('fecha_alta','DESC');
    }
    
    public function getEmails(){
        //var_dump($this->with('usuarios:id,email')->get());
        return Usuario::whereHas('dispositivos')->whereNotNull('email')->pluck('email')->toArray();
    }
    
    /**
     * Devuelve la condicion de vaciado segun dias transcurridos o 
     * directiva desde la App.
     * Si devuelve vaciado se actualiza la fecha de vaciado
     * 
     * @return int
     */
    public function vaciar($nivelTanquePpal){
        $fechaVaciado = new \DateTime($this->fecha_vaciado);
        $interval = $fechaVaciado->diff(new \DateTime());
        $transcurrido = $interval->format('%a');
        
        if($this->vaciar == 1 || $transcurrido >= config('parametros.config.dias_vaciado') ){
            if($nivelTanquePpal < 20){              
                $this->fecha_vaciado = new \DateTime();
                $this->vaciar = 0;
                $this->save();
            }
            $tipoNotificacion = TipoNotificacion::where([
                'tipo' => TipoNotificacion::INFO,
                'pos_string' => TipoNotificacion::VACIADO
            ])->firstOrFail();
            $notificacion = new Notificacion();
            $notificacion->tipoNotificacion()->associate($tipoNotificacion);
            $this->notificaciones()->save($notificacion);
            //Mail::to($this->getEmails())->send(new NotificacionMailer($notificacion));
            return 1;
        }
        return 0;
    }
    
    public static function setDesconexiones(){
        $minDesconexion = config('parametros.config.tiempo_desconexion');
        $fecha = new \DateTime();
        $fecha->sub(new \DateInterval("PT{$minDesconexion}M")); 
        
        $dispositivos = Dispositivo::where('estado', Dispositivo::ON)->whereHas('cultivos.mediciones', function ($query) use ($fecha){
            $query->selectRaw('max(fecha) as ult_fecha')->groupBy('cultivo_id')->having('ult_fecha', '<=' ,$fecha->format('Y-m-d H:i:s'));
        });
        $dispositivos->update(['estado' => Dispositivo::SIN_CONEXION]);
        
        return $dispositivos->get();
    }
}
