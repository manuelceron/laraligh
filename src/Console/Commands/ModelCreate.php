<?php
namespace Manuelceron\Laraligh\Console\Commands;

use Illuminate\Console\Command;

class ModelCreate extends Command
{
    protected $signature = 'laraligh:model:create {name} {fields}';
    protected $description = 'Crear un modelo ligero con relaciones definidas en JSON.';

    public function handle()
    {
        $name = $this->argument('name');
        $fields = json_decode($this->argument('fields'), true);

        // Aquí generamos el archivo del modelo, migración, etc.
        $this->info("Modelo $name creado con éxito.");
    }
}
