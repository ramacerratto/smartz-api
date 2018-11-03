<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Servicios\Notificaciones;


/**
 * Description of evaluarConexion
 *
 * @author RAMA
 */
class EvaluarConexionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'conexion:evaluar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Evalúo los estados de conexión de los dispositivos';

    /**
     * The Notificaciones service.
     *
     * @var Notificaciones
     */
    protected $notificaciones;

    /**
     * Create a new command instance.
     *
     * @param  Notificaciones $notificaciones
     * @return void
     */
    public function __construct(Notificaciones $notificaciones)
    {
        parent::__construct();

        $this->notificaciones = $notificaciones;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $dispositivos = \App\Dispositivo::setDesconexiones();
        foreach($dispositivos as $dispositivo){
            $tipoNotificacion = \App\TipoNotificacion::where([
                'tipo' => \App\TipoNotificacion::ERROR,
                'pos_string' => \App\TipoNotificacion::SINCONEXION
            ])->firstOrFail();
            $notificacion = new \App\Notificacion();
            $notificacion->tipoNotificacion()->associate($tipoNotificacion);
            $dispositivo->notificaciones()->save($notificacion);
            //$this->notificaciones->enviar($notificacion);
        }
    }
}