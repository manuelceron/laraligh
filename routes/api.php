<?php
// routes/api.php
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function() {
    Route::resource('models', 'ModelController');
});
