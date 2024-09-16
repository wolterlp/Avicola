<?php

namespace App\Http\Controllers;

use App\Models\EggProduction;
use App\Models\EggCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Asegúrate de importar DB 
use App\Models\EggInventory;


class EggProductionController extends Controller
{
    public function create()
    {
        $categories = EggCategory::all(); // Obtener todas las categorías de huevos
        return view('eggProduction.create', compact('categories'));
    }

    public function store(Request $request)
    {
        //Validar datos enviados en el formulario

       // dd($request->all());

       $validatedData = $request->validate([
            'date' => 'required|date',
            'quantity' => 'required|integer',
            'egg_category_id' => 'required|exists:egg_categories,id',
        ]);

        // Agregar el ID del usuario logueado
        $validatedData['user_id'] = Auth::id();

        $production = EggProduction::create([
            'date' => $request->input('date'),
            'quantity' => $request->input('quantity'),
            'user_id' => $request->input('user_id'), // Usar el ID del usuario enviado en el formulario
            'egg_category_id' => $request->input('egg_category_id'),
        ]);

        if (!$production) {
            return redirect()->back()->with('error', 'Error al registrar la produción.');
        }


        EggInventory::create([
            'egg_category_id' => $request->input('egg_category_id'),
            'quantity' => $request->input('quantity'),
            'transaction_type' => 'production', //production o sale
            'related_id' => $production->id,
            'user_id' => auth()->id(),
            'transaction_date' => $request->input('date'),
        ]);

        return redirect()->route('eggProduction.create')->with('success', 'Producción de huevos registrada correctamente.');
    }
}
