<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Expense;
use App\Models\EggProduction;
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
        
        // Si no se proporcionan fechas, usa un rango predeterminado (el mes actual)
        if (empty($validated['start_date']) || empty($validated['end_date'])) {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        } else {
            // Formatear las fechas proporcionadas para considerar todo el día
            $startDate = Carbon::parse($validated['start_date'])->startOfDay();
            $endDate = Carbon::parse($validated['end_date'])->endOfDay();
        }

        // Fechas
        $fec_ini = $startDate ->format('d-m-Y');
        $fec_fin = $endDate ->format('d-m-Y');

        // Calcular los ingresos totales
        $totalRevenue = Sale::whereBetween('created_at', [$startDate, $endDate])
                            ->sum('total_price');

        // Calcular los gastos totales
        $totalExpenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
                                ->sum('amount');

        // Calcular la utilidad neta
        $netProfit = $totalRevenue - $totalExpenses;

        // Devolver una vista con los resultados
        return view('reports.net_profit', compact('totalRevenue', 'totalExpenses', 'netProfit', 'fec_ini', 'fec_fin'));

    }

    public function reportSales(Request $request)
    {
        // Validar las fechas recibidas
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);
        
        // Si no se proporcionan fechas, usa un rango predeterminado (el mes actual)
        if (empty($validated['start_date']) || empty($validated['end_date'])) {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        } else {
            // Formatear las fechas proporcionadas para considerar todo el día
            $startDate = Carbon::parse($validated['start_date'])->startOfDay();
            $endDate = Carbon::parse($validated['end_date'])->endOfDay();
        }
        
        $fec_ini = $startDate ->format('d-m-Y');
        $fec_fin = $endDate ->format('d-m-Y');

        // Calcular los ingresos totales
        $totalRevenue = Sale::whereBetween('created_at', [$startDate, $endDate])
                            ->sum('total_price');
    
        // Calcular los gastos totales
        $totalExpenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
                                ->sum('amount');
    
        // Calcular la utilidad neta
        $netProfit = $totalRevenue - $totalExpenses;
    
        // Obtener los datos para los gráficos
        $dailySalesData = Sale::selectRaw('DATE(created_at) as date, SUM(total_price) as total')
                               ->whereBetween('created_at', [$startDate, $endDate])
                               ->groupBy('date')
                               ->orderBy('date')
                               ->get();

        $monthlySalesData = Sale::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(total_price) as total')
                                 ->whereBetween('created_at', [$startDate->startOfYear(), $endDate->endOfYear()])
                                 ->groupBy('year', 'month')
                                 ->orderBy('year')
                                 ->orderBy('month')
                                 ->get();
    
        $yearlySalesData = Sale::selectRaw('YEAR(created_at) as year, SUM(total_price) as total')
                               ->whereBetween('created_at', [$startDate->startOfDecade(), $endDate->endOfDecade()])
                               ->groupBy('year')
                               ->orderBy('year')
                               ->get();

        // Devolver una vista con los resultados
        return view('reports.report_sales', compact('totalRevenue', 'totalExpenses', 'netProfit', 'fec_ini', 'fec_fin', 'dailySalesData', 'monthlySalesData', 'yearlySalesData'));
      
    }
    
    public function reportProduction(Request $request)
    {
        // Validar las fechas recibidas
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);
    
        // Si no se proporcionan fechas, usa un rango predeterminado (el mes actual)
        if (empty($validated['start_date']) || empty($validated['end_date'])) {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        } else {
            // Formatear las fechas proporcionadas para considerar todo el día
            $startDate = Carbon::parse($validated['start_date'])->startOfDay();
            $endDate = Carbon::parse($validated['end_date'])->endOfDay();
        }

        $fec_ini = $startDate ->format('d-m-Y');
        $fec_fin = $endDate ->format('d-m-Y');
    
        // Calcular la producción total de huevos
        $totalProduction = DB::table('egg_productions')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('quantity');
    
        // Obtener los datos para los gráficos
        $dailyProductionData = DB::table('egg_productions')
            ->selectRaw('DATE(created_at) as date, SUM(quantity) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();
    
        $monthlyProductionData = DB::table('egg_productions')
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(quantity) as total')
            ->whereBetween('created_at', [$startDate->startOfYear(), $endDate->endOfYear()])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('year')
            ->orderBy('month')
            ->get();
    
        $yearlyProductionData = DB::table('egg_productions')
            ->selectRaw('YEAR(created_at) as year, SUM(quantity) as total')
            ->whereBetween('created_at', [$startDate->startOfDecade(), $endDate->endOfDecade()])
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy('year')
            ->get();
    
        // Obtener la producción por categoría
        $productionByCategory = DB::table('egg_productions')
            ->join('egg_categories', 'egg_productions.egg_category_id', '=', 'egg_categories.id')
            ->select('egg_categories.category as category', DB::raw('SUM(egg_productions.quantity) as total'))
            ->whereBetween('egg_productions.created_at', [$startDate, $endDate])
            ->groupBy('egg_categories.category')
            ->get();
    
        // Devolver una vista con los resultados
        return view('reports.report_production', compact(
            'totalProduction', 
            'fec_ini', 
            'fec_fin', 
            'dailyProductionData', 
            'monthlyProductionData', 
            'yearlyProductionData', 
            'productionByCategory'
        ));
    }
    
    public function reportExpenses(Request $request)
    {
        // Validar las fechas recibidas
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);
    
        // Si no se proporcionan fechas, usa un rango predeterminado (el mes actual)
        if (empty($validated['start_date']) || empty($validated['end_date'])) {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        } else {
            // Formatear las fechas proporcionadas para considerar todo el día
            $startDate = Carbon::parse($validated['start_date'])->startOfDay();
            $endDate = Carbon::parse($validated['end_date'])->endOfDay();
        }
    
        $fec_ini = $startDate->format('d-m-Y');
        $fec_fin = $endDate->format('d-m-Y');
    
        // Calcular el total de gastos
        $totalExpenses = DB::table('expenses')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');
    
        // Obtener los datos para los gráficos diarios
        $dailyExpensesData = DB::table('expenses')
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();
    
        // Obtener los datos para los gráficos mensuales
        $monthlyExpensesData = DB::table('expenses')
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(amount) as total')
            ->whereBetween('created_at', [$startDate->startOfYear(), $endDate->endOfYear()])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('year')
            ->orderBy('month')
            ->get();
    
        // Obtener los datos para los gráficos anuales
        $yearlyExpensesData = DB::table('expenses')
            ->selectRaw('YEAR(created_at) as year, SUM(amount) as total')
            ->whereBetween('created_at', [$startDate->startOfDecade(), $endDate->endOfDecade()])
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy('year')
            ->get();
        
        // Devolver una vista con los resultados
        return view('reports.report_expenses', compact(
            'totalExpenses', 
            'fec_ini', 
            'fec_fin', 
            'dailyExpensesData', 
            'monthlyExpensesData', 
            'yearlyExpensesData'
        ));
    }
    
    
}
