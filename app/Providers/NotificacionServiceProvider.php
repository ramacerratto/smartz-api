<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class NotificacionServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register(){
        $this->app->bind('App\Servicios\Notificacion', function ($app) {
            return new Notificacion();
        });
    }
    
    public function boot(){
    }
}
