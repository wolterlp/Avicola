<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EggProductionController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VentasController;


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
Route::get('eggProduction/create', [EggProductionController::class, 'create'])->middleware(['auth', 'verified'])->name('eggProduction.create');

// Ruta para almacenar los datos del formulario
Route::post('eggProduction', [EggProductionController::class, 'store'])->middleware(['auth', 'verified'])->name('eggProduction.store');

// Ruta para mostrar el inventario de producción de huevos
Route::get('/inventories/view', [InventoryController::class, 'view'])->middleware(['auth', 'verified'])->name('inventories.view');
Route::post('/produccion/agregar', [InventoryController::class, 'addProduction'])->middleware(['auth', 'verified'])->name('eggProduction.store');
Route::post('/ventas/agregar', [InventoryController::class, 'addSale'])->middleware(['auth', 'verified'])->name('sales.store');

// Rutas ingresos por ventas
Route::get('/sales/revenue', [SaleController::class, 'showRevenueForm'])->middleware(['auth', 'verified'])->name('sales.revenue.form');
Route::post('/sales/revenue', [SaleController::class, 'calculateRevenue'])->middleware(['auth', 'verified'])->name('sales.revenue.calculate');

// Ruta para ver reporte utilidad
Route::get('/report/net-profit', [ReportController::class, 'netProfit'])->middleware(['auth', 'verified'])->name('report.netProfit');
Route::get('/report/report_sales', [ReportController::class, 'reportSales'])->middleware(['auth', 'verified'])->name('report.reportSales');
Route::get('/report/report_production', [ReportController::class, 'reportProduction'])->middleware(['auth', 'verified'])->name('report.reportProduction');
Route::get('/report/report_expenses', [ReportController::class, 'reportExpenses'])->middleware(['auth', 'verified'])->name('report.reportExpenses');

// Ruta para Historial de Ventas
Route::get('sales/history', [SaleController::class, 'historialVentas'])->middleware(['auth', 'verified'])->name('sales.history');

// Ruta para Ventas
Route::resource('sales', SaleController::class);

// Ruta para registro de gastos
Route::resource('expenses', ExpenseController::class);

/* Modales  Ruta para alert */
Route::get('/modal-alert', function () {
    return view('modal-alert');
});

// Ruta para modal Sales 
Route::get('/sales/modal-show', function () {
    return view('/sales/modal-show');
});

require __DIR__.'/auth.php';
