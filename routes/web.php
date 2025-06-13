<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\PresentacionController;
use App\Http\Controllers\TipoEventoController;
use App\Http\Controllers\UbicacionController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('template');
});

Route::view('/panel', 'panel.index')->name('panel');

Route::resource('categorias', CategoriaController::class);
Route::resource('marcas', MarcaController::class);
Route::resource('presentaciones', PresentacionController::class);
Route::resource('ubicaciones', UbicacionController::class);
Route::resource('tiposEvento', TipoEventoController::class);


Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/401', function () {
    return view('pages.401');
});

Route::get('/404', function () {
    return view('pages.404');
});

Route::get('/500', function () {
    return view('pages.500');
});

