<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB; // Asegúrate de importar DB 
use Illuminate\Http\Request;
use App\Models\EggInventory;
use App\Models\EggCategory;
use App\Models\EggProduction;
use App\Models\Sale;

class InventoryController extends Controller
{
    //
    public function view()
    {
        // Obtener el inventario agrupado por categoría de huevo
        $inventory = EggInventory::select('egg_inventory.egg_category_id')
            ->selectRaw('SUM(CASE WHEN egg_inventory.transaction_type = "production" THEN egg_inventory.quantity ELSE 0 END) as total_produced')
            ->selectRaw('SUM(CASE WHEN egg_inventory.transaction_type = "sale" THEN egg_inventory.quantity ELSE 0 END) as total_sold')
            ->selectRaw('SUM(CASE WHEN egg_inventory.transaction_type = "production" THEN egg_inventory.quantity ELSE 0 END) - SUM(CASE WHEN egg_inventory.transaction_type = "sale" THEN egg_inventory.quantity ELSE 0 END) as total_inventory')
            ->join('egg_categories', 'egg_inventory.egg_category_id', '=', 'egg_categories.id')
            ->addSelect('egg_categories.category', 'egg_categories.description')
            ->groupBy('egg_inventory.egg_category_id', 'egg_categories.category', 'egg_categories.description')
            ->orderBy('egg_category_id', 'desc') // Orden ascendente
            ->get();

       // dd($inventory);

        return view('inventories.view', compact('inventory'));
        //return "hola";
    }

    public function addProduction(Request $request)
    {
        $production = EggProduction::create([
            'date' => $request->input('date'),
            'quantity' => $request->input('quantity'),
            'user_id' => auth()->id(),
            'egg_category_id' => $request->input('egg_category_id'),
        ]);

        EggInventory::create([
            'egg_category_id' => $request->input('egg_category_id'),
            'quantity' => $request->input('quantity'),
            'transaction_type' => 'production',
            'related_id' => $production->id,
            'user_id' => auth()->id(),
            'transaction_date' => $request->input('date'),
        ]);

        return redirect()->route('eggProduction.create')->with('success', 'Producción de huevos registrada y actualizada en inventario correctamente.');
    }

    public function addSale(Request $request)
    {
        $sale = Sale::create([
            'date' => $request->input('date'),
            'quantity' => $request->input('quantity'),
            'user_id' => auth()->id(),
            'egg_category_id' => $request->input('egg_category_id'),
        ]);

        EggInventory::create([
            'egg_category_id' => $request->input('egg_category_id'),
            'quantity' => -1 * $request->input('quantity'), // Cantidad negativa porque es una venta
            'transaction_type' => 'sale',
            'related_id' => $sale->id,
            'user_id' => auth()->id(),
            'transaction_date' => $request->input('date'),
        ]);

        return redirect()->route('sales.create')->with('success', 'Venta registrada y actualizada en inventario correctamente.');
    }
}
