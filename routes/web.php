<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EggProductionController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Ruta para mostrar el formulario de registro de producción de huevos
Route::get('eggProduction/create', [EggProductionController::class, 'create'])->name('eggProduction.create');

// Ruta para almacenar los datos del formulario
Route::post('eggProduction', [EggProductionController::class, 'store'])->name('eggProduction.store');

// Ruta para mostrar el inventario de producción de huevos
Route::get('/inventories/view', [InventoryController::class, 'view'])->name('inventories.view');
Route::post('/produccion/agregar', [InventoryController::class, 'addProduction'])->name('eggProduction.store');
Route::post('/ventas/agregar', [InventoryController::class, 'addSale'])->name('sales.store');

// Rutas ingresos por ventas
Route::get('/sales/revenue', [SaleController::class, 'showRevenueForm'])->name('sales.revenue.form');
Route::post('/sales/revenue', [SaleController::class, 'calculateRevenue'])->name('sales.revenue.calculate');


// Ruta para ver reporte utilidad
Route::get('/report/net-profit', [ReportController::class, 'netProfit'])->name('report.netProfit');

// Ruta para Ventas
Route::resource('sales', SaleController::class);

// Ruta para registro de gastos
Route::resource('expenses', ExpenseController::class);



Route::get('/test', function () {
    return 'Ruta de prueba funcionando';
});


require __DIR__.'/auth.php';
