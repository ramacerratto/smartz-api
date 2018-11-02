<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Servicios\Notificaciones;

class NotificacionServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register(){
        $this->app->bind(Notificaciones::class, function ($app) {
            return new Notificaciones();
        });
    }
    
    public function boot(){
    }
}
