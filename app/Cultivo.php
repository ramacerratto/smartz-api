<?php

namespace App;

use App\BaseModel;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacionMailer;

class Cultivo extends BaseModel
{

    const CREATED_AT = 'fecha_inicio';
    
    const COSECHABLE = 2;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cultivos';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['dispositivo_id', 'rutina_cultivo_id', 'estado'];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['fecha_fin','dias_progreso','dias_totales','nombre_rutina_cultivo'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
    
    protected $dates = ['fecha_inicio', 'fecha_modificacion'];
    
    protected $attributes = [
        'estado' => self::ACTIVO
    ];

    public static $rules = [
        'dispositivo_id' => 'required|exists:dispositivos,id',
        'rutina_cultivo_id' => 'required|exists:rutinas_cultivo,id',
    ];
    
    public function getFechaFinAttribute(){
        $rutinasCultivo = $this->belongsTo('App\RutinaCultivo','rutina_cultivo_id')->with(['fasesRutinaCultivo.fase' => function ($query) {
            $query->orderBy('orden', 'asc');
        }])->first();
        
        $duracionTotal = 0;
        foreach($rutinasCultivo->fasesRutinaCultivo as $fase){
            $duracionTotal += $fase->duracion;
        }
        
        $fechaInicio = new \DateTime($this->fecha_inicio);
        $fechaInicio->add(new \DateInterval("P{$duracionTotal}D")); // P1D means a period of 1 day
        
        return $fechaInicio->format('Y-m-d');
    }
    
    public function getDiasProgresoAttribute(){
        $fechaInicio = new \DateTime($this->fecha_inicio);
        $interval = $fechaInicio->diff(new \DateTime());
        return $interval->format('%a');
    }
    
    public function getDiasTotalesAttribute(){
        $fechaInicio = new \DateTime($this->fecha_inicio);
        $interval = $fechaInicio->diff(new \DateTime($this->fecha_fin));
        return $interval->format('%a')+1;
    }
    
    public function getNombreRutinaCultivoAttribute(){
        return optional($this->rutinaCultivo)->nombre;
    }
    
    /**
     * Obtiene la rutina de cultivo del cultivo
     */
    public function rutinaCultivo()
    {
        return $this->belongsTo('App\RutinaCultivo', 'rutina_cultivo_id');
    }
    
    /**
     * Obtiene las mediciones del cultivo 
     */
    public function mediciones()
    {
        return $this->hasMany('App\Medicion');
    }
    
    /**
     * Obtiene el dispositivo del cultivo
     */
    public function dispositivo(){
        return $this->belongsTo('App\Dispositivo');
    }
    
    /**
     * Devuelve la fase actual o false si el ciclo ya termino
     * 
     * @return boolean
     */
    public function faseActual(){
        $fechaInicio = new \DateTime($this->fecha_inicio);
        $interval = $fechaInicio->diff(new \DateTime());
        $transcurrido = $interval->format('%a');
        $rutinasCultivo = $this->belongsTo('App\RutinaCultivo','rutina_cultivo_id')->with(['fasesRutinaCultivo.fase' => function ($query) {
            $query->orderBy('orden', 'asc');
        }])->first();
        
        foreach($rutinasCultivo->fasesRutinaCultivo as &$fase){
            $transcurrido -= $fase->duracion;
            if($transcurrido <= 0){
                break;
            }
        }
        if($transcurrido == 0){
            $posString = ($fase->esFinal())?TipoNotificacion::COSECHA:TipoNotificacion::CAMBIOFASE;
            $tipoNotificacion = TipoNotificacion::where([
                'tipo' => TipoNotificacion::INFO,
                'pos_string' => $posString
            ])->firstOrFail();
            $notificacion = new Notificacion();
            $notificacion->tipoNotificacion()->associate($tipoNotificacion);
            $notificacion->cultivo()->associate($this);
            $this->dispositivo->notificaciones()->save($notificacion);
            Mail::to($this->dispositivo->getEmails())->send(new NotificacionMailer($notificacion));
        }
        return $fase;
    }
}
