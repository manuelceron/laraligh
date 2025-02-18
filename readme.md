# Laralight Package

`Laralight` es un paquete diseñado para agilizar la creación de modelos, migraciones, controladores y CRUDs en Laravel mediante el uso de comandos CLI personalizados. Este paquete es ideal para desarrolladores que buscan reducir el tiempo de configuración inicial y mantener una estructura coherente y limpia en sus proyectos.

## Propósito
El propósito de este `README` es servir como una guía interactiva y procesable para configurar y usar el paquete `Laralight`. Se incluyen ejemplos claros que, al ser procesados por una IA, puedan generar automáticamente las acciones necesarias.

---

## Instalación

### Requisitos
- Laravel >= 8.x
- PHP >= 7.4

### Instalación del paquete
Ejecuta el siguiente comando para instalar el paquete:

```bash
composer require manuelceron/Laralight
```

### Publicación de configuraciones y vistas

```bash
php artisan vendor:publish --provider="Manuelceron\Laralight\LaralightServiceProvider"
```

---

## Comandos Disponibles

### Crear un modelo

Crea un modelo junto con su migración y estructura básica:

```bash
php artisan laralight:model:create ModelName '{"field1":"type","field2":"type|nullable"}'
```

#### Ejemplo Procesable

```bash
php artisan laralight:model:create Project '{"name":"string","client":"string","start_date":"string","end_date":"string|nullable","status":"string","budget":"double","notes":"string|nullable"}'
```

Este comando creará un modelo `Project` con los siguientes campos:
- `name`: string (requerido)
- `client`: string (requerido)
- `start_date`: string (requerido)
- `end_date`: string (opcional)
- `status`: string (requerido)
- `budget`: double (requerido)
- `notes`: string (opcional)

### Crear un CRUD completo

Genera un CRUD completo para un modelo:

```bash
php artisan laralight:crud ModelName '{"field1":"type","field2":"type|nullable"}'
```

#### Ejemplo Procesable

```bash
php artisan laralight:crud Project '{"name":"string","client":"string","start_date":"string","end_date":"string|nullable","status":"string","budget":"double","notes":"string|nullable"}'
```

---

## Ejecución de Migraciones

Después de crear los modelos y migraciones, no olvides ejecutar las migraciones para aplicar los cambios en la base de datos:

```bash
php artisan migrate
```

---

## Notas Adicionales
- Asegúrate de que el paquete esté configurado correctamente después de publicarlo.
- Los campos aceptan cualquier tipo soportado por Laravel (string, integer, double, boolean, etc.).
- Puedes personalizar aún más los modelos generados según tus necesidades.

---

## Soporte
Si encuentras problemas o tienes dudas, por favor contacta al desarrollador o consulta la documentación oficial del paquete.

---

> **Nota:** Este `README` está diseñado para que sea fácil de entender tanto por humanos como por sistemas de IA que procesen el archivo para ejecutar tareas automáticamente.
