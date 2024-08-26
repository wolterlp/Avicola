<?php

namespace App\Http\Controllers;

use App\Models\EggProduction;
use App\Models\EggCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Asegúrate de importar DB 


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

 /*   
        // Ver la consulta SQL que se va a ejecutar
        DB::enableQueryLog(); // Activar el registro de consultas
        DB::table('egg_productions')
            ->insert($validatedData);

        // Mostrar la consulta SQL generada y detener la ejecución
        dd(DB::getQueryLog());
*/

        EggProduction::create([
            'date' => $request->input('date'),
            'quantity' => $request->input('quantity'),
            'user_id' => $request->input('user_id'), // Usar el ID del usuario enviado en el formulario
            'egg_category_id' => $request->input('egg_category_id'),
        ]);



        return redirect()->route('eggProduction.create')->with('success', 'Producción de huevos registrada correctamente.');
    }
}
