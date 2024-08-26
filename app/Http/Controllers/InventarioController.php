<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EggCategory;
use App\Models\EggProduction;
use Illuminate\Support\Facades\DB;


class InventarioController extends Controller
{
    public function view()
    {
         // Consulta para obtener el total de huevos por categoría
         $inventario = EggProduction::select('egg_category_id', DB::raw('SUM(quantity) as total'))
         ->groupBy('egg_category_id')
         ->with('eggCategory') // Relación con EggCategory
         ->get();

        //$categories = EggCategory::all(); // Obtener todas las categorías de huevos
        return view('eggProduction.inventario', compact('inventario'));
    }
}
