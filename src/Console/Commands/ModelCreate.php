<?php
namespace Manuelceron\Laraligh\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ModelCreate extends Command
{
    protected $signature = 'laraligh:model:create {name} {fields}';
    protected $description = 'Crear un modelo ligero con relaciones definidas en JSON.';

    public function handle()
    {
        $name = $this->argument('name');
        $fields = json_decode($this->argument('fields'), true);

        // Crear el modelo
        $this->createModel($name);

        // Crear la migración
        $this->createMigration($name, $fields);

        // Crear el controlador
        $this->createController($name);

        $this->info("Modelo $name, migración y controlador creados con éxito.");
    }

    public function createModel($modelName)
    {
        $modelPath = app_path('Models');
        if (!file_exists($modelPath)) {
            mkdir($modelPath, 0755, true);
        }

        $modelStub = file_get_contents(__DIR__ . '/stubs/model.stub');
        $modelContent = str_replace('{{modelName}}', $modelName, $modelStub);

        file_put_contents($modelPath . '/' . $modelName . '.php', $modelContent);
    }

    public function createMigration($modelName, $fields)
    {
        $migrationName = 'create_' . Str::snake($modelName) . '_table';
        $migrationFileName = date('Y_m_d_His') . '_' . $migrationName . '.php';
        
        $migrationPath = database_path('migrations');
        
        $fieldsSchema = [];
        foreach ($fields as $field => $type) {
            $fieldsSchema[] = "\$table->{$type}('{$field}');";
        }

        $migrationStub = file_get_contents(__DIR__ . '/stubs/migration.stub');
        $migrationContent = str_replace(
            ['{{migrationName}}', '{{tableName}}', '{{fields}}'],
            [$migrationName, Str::snake($modelName), implode("\n\t\t", $fieldsSchema)],
            $migrationStub
        );
        
        file_put_contents($migrationPath . '/' . $migrationFileName, $migrationContent);
    }

    public function createController($modelName)
    {
        $controllerName = $modelName . 'Controller';
        $controllerPath = app_path('Http/Controllers');
        
        $controllerStub = file_get_contents(__DIR__ . '/stubs/controller.stub');
        
        $controllerContent = str_replace(
            ['{{modelName}}', '{{modelNameLower}}'],
            [$modelName, strtolower($modelName)],
            $controllerStub
        );
        
        file_put_contents($controllerPath . '/' . $controllerName . '.php', $controllerContent);
    }
}
