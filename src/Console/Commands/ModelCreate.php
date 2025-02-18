<?php
namespace Manuelceron\Laralight\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class ModelCreate extends Command
{
    protected $signature = 'laraligh:model:create {name} {fields}';
    protected $description = 'Crear un modelo ligero con relaciones definidas en JSON.';

    public function handle()
    {
        $name = $this->argument('name');
        $fields = $this->argument('fields');  // Obtenemos los campos como string
        // Intentamos decodificar el JSON a un array
        $formattedJson = '' . substr($fields, 1, -1) . '';
        $fieldsArray = json_decode($formattedJson, true);
        
        #print_r($fields);
       # print_r($formattedJson);
        print_r($fieldsArray);
        // Verificar si la decodificación fue exitosa
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error("El formato de los campos es incorrecto o no se pudo decodificar.");
            return;
        }

        // Aquí ya puedes trabajar con el array de los campos.// Crear el modelo
        $this->createModel($name);

        // Crear la migración
        $this->createMigration($name, $fieldsArray);

        // Crear el controlador
        $this->createController($name);
        $controllerName = $name . 'Controller';
        $routeFile = base_path('routes/web.php');
        
        $routeContent = $this->generateRouteContent($name, $controllerName);

        // Agregar la nueva ruta en el archivo api.php
        File::append($routeFile, $routeContent);


          // Ejecutar la migración
    $this->info("Ejecutando las migraciones...");
    try {
        Artisan::call('migrate', [
            '--force' => true, // Forzar ejecución en entornos de producción
        ]);
        $this->info("Migraciones ejecutadas con éxito.");
    } catch (\Exception $e) {
        $this->error("Error al ejecutar las migraciones: " . $e->getMessage());
    }

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
        $modelContent = str_replace('{{name}}', strtolower($modelName), $modelContent);

        file_put_contents($modelPath . '/' . $modelName . '.php', $modelContent);
    }

    public function createMigration($modelName, $fields)
    {
        $migrationName = 'create_' . Str::snake($modelName) . '_table';
        $migrationFileName = date('Y_m_d_His') . '_' . $migrationName . '.php';

        $migrationPath = database_path('migrations');

        $fieldsSchema = [];
        foreach ($fields as $field => $type) {
            // Revisar si el tipo incluye '|nullable'
            if (str_contains($type, '|nullable')) {
                $type = str_replace('|nullable', '', $type); // Remover '|nullable'
                $fieldsSchema[] = "\$table->{$type}('{$field}')->nullable();";
            } else {
                $fieldsSchema[] = "\$table->{$type}('{$field}');";
            }
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
        $controllerPath = app_path('Http/Controllers\Api');
        // Verificar si la carpeta Api existe; si no, crearla
        if (!file_exists($controllerPath)) {
            mkdir($controllerPath, 0755, true); // Crear la carpeta con permisos recursivos
        }
        $controllerStub = file_get_contents(__DIR__ . '/stubs/controller.stub');
        
        $controllerContent = str_replace(
            ['{{modelName}}', '{{modelNameLower}}'],
            [$modelName, strtolower($modelName)],
            $controllerStub
        );
        
        file_put_contents($controllerPath . '/' . $controllerName . '.php', $controllerContent);
    }
    private function generateRouteContent($modelName, $controllerName)
    {
        // Pluralizar y convertir el nombre del modelo a snake_case
        $routeName = Str::plural(Str::snake($modelName));
        $routeStub = file_get_contents(__DIR__ . '/stubs/route.stub');
        $routeContent = str_replace('{{routeName}}', $routeName, $routeStub);
        $routeContent = str_replace('{{controllerName}}', $controllerName, $routeContent);
        return $routeContent;
    }
    
}
