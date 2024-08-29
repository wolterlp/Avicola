<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Expense;
use Carbon\Carbon;


use App\Models\EggCategory;
use App\Models\EggInventory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    //
    public function netProfit(Request $request)
    {
      
        // Validar las fechas recibidas
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);
        
        // Si no se proporcionan fechas, usa un rango predeterminado (por ejemplo, el mes actual)
        if (empty($validated['start_date']) || empty($validated['end_date'])) {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        } else {
            // Formatear las fechas proporcionadas para considerar todo el día
            $startDate = Carbon::parse($validated['start_date'])->startOfDay();
            $endDate = Carbon::parse($validated['end_date'])->endOfDay();
        }
        // Calcular los ingresos totales
        $totalRevenue = Sale::whereBetween('created_at', [$startDate, $endDate])
                            ->sum('total_price');

        // Calcular los gastos totales
        $totalExpenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
                                ->sum('amount');

        // Calcular la utilidad neta
        $netProfit = $totalRevenue - $totalExpenses;

        // Devolver una vista con los resultados
       return view('reports.net_profit', compact('totalRevenue', 'totalExpenses', 'netProfit'));
        
      // return "Hola netProfit";
    }
}
