<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CondicionController;
use App\Http\Controllers\HistorialController;
use App\Http\Controllers\homeController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\logoutController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\profileController;
use App\Http\Controllers\roleController;
use App\Http\Controllers\TipoEventoController;
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\userController;
use Illuminate\Support\Facades\Route;



Route::get('/', [homeController::class, 'index'])->name('panel');

Route::get('/historiales/productos/{producto}/data', [HistorialController::class, 'getProductData'])->name('historiales.getProductData');

Route::resources([
    'categorias' => CategoriaController::class,
    'marcas' => MarcaController::class,
    'ubicaciones' => UbicacionController::class,
    'condiciones' => CondicionController::class,
    'tiposEvento' => TipoEventoController::class,
    'productos' => ProductoController::class,
    'historiales' => HistorialController::class,
    'users' => userController::class,
    'roles' => roleController::class,
    'profile' => profileController::class,
]);

Route::get('/login', [loginController::class, 'index'])->name('login');
Route::post('/login', [loginController::class, 'login']);
Route::get('/logout', [logoutController::class, 'logout'])->name('logout');

Route::get('/401', function () {
    return view('pages.401');
});

Route::get('/404', function () {
    return view('pages.404');
});

Route::get('/500', function () {
    return view('pages.500');
});

Route::fallback(function () {
    return response()->view('pages.404', [], 404);
});


Route::get('/historiales/{historial}/pdf', [HistorialController::class, 'downloadPdf'])->name('historiales.downloadPdf');

