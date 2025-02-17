<?php
namespace Manuelceron\Laraligh\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaralighTest extends TestCase
{
    use RefreshDatabase;

    public function testModelCreation()
    {
        // Prueba de la creación del modelo
        $this->artisan('laraligh:model:create Project \'{"name":"string","client":"string","budget":"double"}\'')
             ->expectsOutput('Modelo Project creado con éxito.');

        // Verifica que el archivo del modelo se haya creado
        $this->assertFileExists(app_path('Models/Project.php'));
    }
}
