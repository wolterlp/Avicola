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
    public function historialVentas(Request $request)
    {

        // Validar las fechas recibidas
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);
        
        // Si no se proporcionan fechas, usa un rango predeterminado (del dia actual)
        if (empty($validated['start_date']) || empty($validated['end_date'])) {
            $startDate = Carbon::now()->startOfDay();
            $endDate = Carbon::now()->endOfDay();
        } else {
            // Formatear las fechas proporcionadas para considerar todo el día
            $startDate = Carbon::parse($validated['start_date'])->startOfDay();
            $endDate = Carbon::parse($validated['end_date'])->endOfDay();
        }

        // fechas usadas en la consulta
        $fec_ini = $startDate ->format('d-m-Y');
        $fec_fin = $endDate ->format('d-m-Y');


        // realizamos la consulta
        $sales = Sale::with('eggCategory', 'user') 
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->simplePaginate(6); // 10 ventas por página

        return view('sales.history', compact('sales', 'fec_ini', 'fec_fin'));
    }

    public function editSale(Sale $sale)
    {

        $eggCategories = EggCategory::all();

        return view('sales.edit',['sale' => $sale], compact('eggCategories'));
    }

    public function create()
    {
        $eggCategories = EggCategory::all();
        return view('sales/create', compact('eggCategories'));
    }

    

    public function update(Request $request,Sale $sale)
    {
        $validated = $request->validate([
            'egg_category_id' => 'required|exists:egg_categories,id',
            'quantity' => 'required|integer|min:1',
            'price_per_unit' => 'required|numeric|min:0.01',
        ]);

        $currentDate = Carbon::today(); // Fecha actual para buscar solo del dia de hoy

        $eggInventory = EggInventory::where('egg_category_id', $sale->egg_category_id)
            ->where('user_id', $sale->user_id)
            ->where('related_id', $sale->id)
            ->where('transaction_type', 'sale')
            ->whereDate('created_at', $currentDate)
            ->first();

        if ($eggInventory) {
            // actualizamos la venta
            $sale->update($validated);

            // Actualizar el inventario existente
            $eggInventory->update([
                'quantity' => $sale->quantity, // Actualiza la cantidad en inventario con el valor de la venta
            ]);

            return to_route('sales.history')->with('status', __('Sale update successfully!'));

        } else{
            return redirect()->back()->with('error', __('Solo puede actualizar ventas del día!'));
   
        }

        
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
            'total_price' => $totalPrice
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


        return redirect()->route('sales.create')->with('success', 'Venta registrada correctamente.');
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
        */
        
        return view('sales.revenue', [
            
            'startDate' => $validated['start_date'],
            'endDate' => $validated['end_date'],
            'totalRevenue' => $totalRevenue,
        ]);
    }

}
