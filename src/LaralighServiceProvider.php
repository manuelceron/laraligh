<?php
namespace Manuelceron\Laraligh;

use Illuminate\Support\ServiceProvider;

class LaralighServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laraligh.php', 'laraligh');
    }

    public function boot()
    {
        // Registrar el comando CLI
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\Commands\ModelCreate::class,
            ]);
        }
    }
}
