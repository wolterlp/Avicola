<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\EggCategory;
use App\Models\EggInventory;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    //
    public function historialVentas()
    {
        $sales = Sale::with('eggCategory', 'user')
        ->orderBy('created_at', 'desc')
        ->get();
       
        return view('sales.history', compact('sales'));
    }

    public function create()
    {
        $eggCategories = EggCategory::all();
        return view('sales.create', compact('eggCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'egg_category_id' => 'required|exists:egg_categories,id',
            'quantity' => 'required|integer|min:1',
            'price_per_unit' => 'required|numeric|min:0.01',
        ]);
        
        $totalPrice = $request->quantity * $request->price_per_unit;

        // Crear el registro en la tabla 'sales'
        $sale = Sale::create([
            'egg_category_id' => $request->input('egg_category_id'),
            'user_id' => auth()->id(), // ID del usuario autenticado
            'quantity' => $request->input('quantity'),
            'price_per_unit' => $request->input('price_per_unit'),
            'total_price' => $request->input('quantity') * $request->input('price_per_unit'),
        ]);

        
        if (!$sale) {
            return redirect()->back()->with('error', 'Error al registrar la venta.');
        }

        $transactionDate = $request->input('transaction_date') ?? now();

        // Registrar la transacción en la tabla 'egg_inventory'
        try {
            EggInventory::create([
                'egg_category_id' => $request->input('egg_category_id'),
                'quantity' => $request->input('quantity'),
                'transaction_type' => 'sale',
                'related_id' => $sale->id,
                'user_id' => auth()->id(),
                'transaction_date' => $transactionDate,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al registrar en el inventario: ' . $e->getMessage());
        }


        return redirect()->route('sales.index')->with('success', 'Venta registrada correctamente.');
    }
    
    public function show($id)
    {
        $sale = Sale::with('eggCategory', 'user')->findOrFail($id);
        return view('sales.show', compact('sale'));

    }

    public function modalContentShow()
    {
        /**
         * Muestra el contenido del modal.
         *
         * @return \Illuminate\View\View
         */
        return view('modal-show');
    }

    // Método para mostrar el formulario de ingresos
    public function showRevenueForm()
    {
        $sales = Sale::with('eggCategory')->get();
        return view('sales.revenue', compact('sales'));
    }

    // Método para calcular y mostrar los ingresos
    public function calculateRevenue(Request $request)
    {
 
        $validated = $request->validate([
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d',
        ]);

        $startDate = Carbon::parse($request->input('start_date'))->startOfDay()->toDateTimeString();
        $endDate = Carbon::parse($request->input('end_date'))->endOfDay()->toDateTimeString();

        $totalRevenue = DB::table('sales')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_price');

        //    dd($startDate, $endDate);
                    
        /*
        // Ver la consulta SQL
        $sql = Sale::whereBetween('created_at', [$validated['start_date'], $validated['end_date']])
        ->toSql();

        dd($sql);                    
        */
        
        return view('sales.revenue', [
            
            'startDate' => $validated['start_date'],
            'endDate' => $validated['end_date'],
            'totalRevenue' => $totalRevenue,
        ]);
    }

}
