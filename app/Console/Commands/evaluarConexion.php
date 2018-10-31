<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;


/**
 * Description of evaluarConexion
 *
 * @author RAMA
 */
class evaluarConexion extends Command
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
     * The drip e-mail service.
     *
     * @var DripEmailer
     */
    protected $drip;

    /**
     * Create a new command instance.
     *
     * @param  DripEmailer  $drip
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
        $dispositivos = \App\Dispositivo::evaluarConexion();
        foreach($dispositivos as $dispositivo){
            $tipoNotificacion = TipoNotificacion::where([
                'tipo' => \App\TipoNotificacion::ERROR,
                'pos_string' => \App\TipoNotificacion::SINCONEXION
            ])->firstOrFail();
            $notificacion = new Notificacion();
            $notificacion->tipoNotificacion()->associate($tipoNotificacion);
            $dispositivo->notificaciones()->save($notificacion);
            $this->notificaciones->enviar($notificacion);
        }
    }
}