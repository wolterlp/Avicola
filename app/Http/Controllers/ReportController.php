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
        $dailySalesData = 
        Sale::selectRaw('DATE(sales.created_at) as date, egg_categories.category, SUM(sales.quantity) as total_eggs, SUM(sales.total_price) as total')
            ->join('egg_categories', 'sales.egg_category_id', '=', 'egg_categories.id')
            ->whereBetween('sales.created_at', [$startDate, $endDate])
            ->groupBy('date', 'egg_categories.category')
            ->orderBy('date')
            ->get();

        $dailyPaginadoSalesData = 
        Sale::selectRaw('DATE(sales.created_at) as date, egg_categories.category, SUM(sales.quantity) as total_eggs, SUM(sales.total_price) as total')
            ->join('egg_categories', 'sales.egg_category_id', '=', 'egg_categories.id')
            ->whereBetween('sales.created_at', [$startDate, $endDate])
            ->groupBy('date', 'egg_categories.category')
            ->orderBy('date', 'ASC')
            ->simplePaginate(5, ['*'], 'dailyPage');

        $monthlySalesData = 
            Sale::selectRaw('YEAR(sales.created_at) as year, MONTH(sales.created_at) as month, SUM(sales.quantity) as total_eggs, SUM(sales.total_price) as total')
            ->whereBetween('sales.created_at', [$startDate->startOfYear(), $endDate->endOfYear()])
            ->groupBy('year', 'month')
            ->orderBy('year', 'ASC')
            ->orderBy('month', 'ASC')
            ->get();

        $monthlyPaginadoSalesData = 
            Sale::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(quantity) as total_eggs, SUM(sales.total_price) as total')
            ->whereBetween('created_at', [$startDate->startOfYear(), $endDate->endOfYear()])
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->simplePaginate(5, ['*'], 'monthlyPage');

        $yearlySalesData = Sale::selectRaw('YEAR(created_at) as year, SUM(quantity) as total_eggs, SUM(sales.total_price) as total')
            ->whereBetween('created_at', [$startDate->startOfDecade(), $endDate->endOfDecade()])
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        $yearlyPaginadoSalesData = Sale::selectRaw('YEAR(created_at) as year, SUM(quantity) as total_eggs, SUM(sales.total_price) as total')
            ->whereBetween('created_at', [$startDate->startOfDecade(), $endDate->endOfDecade()])
            ->groupBy('year')
            ->orderBy('year')
            ->simplePaginate(5, ['*'], 'yearlyPage');

        // Devolver una vista con los resultados
        return view('reports.report_sales', compact(
            'totalRevenue', 
            'totalExpenses', 
            'netProfit', 
            'fec_ini', 
            'fec_fin', 
            'dailyPaginadoSalesData',
            'dailySalesData', 
            'monthlyPaginadoSalesData',
            'monthlySalesData', 
            'yearlyPaginadoSalesData',
            'yearlySalesData'));
            
      
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
   
        // Calcular la producción total de huevos
        $totalProduction = DB::table('egg_productions')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('quantity');
    
        $productionByCategory = EggProduction::select(
                'egg_categories.category as category',
                DB::raw('SUM(egg_productions.quantity) as total')
            )
            ->join('egg_categories', 'egg_productions.egg_category_id', '=', 'egg_categories.id')
            ->whereBetween('egg_productions.created_at',  [$startDate, $endDate])
            ->groupBy('egg_categories.category', 'egg_productions.egg_category_id')
            ->orderBy('egg_productions.egg_category_id', 'ASC')
            ->get();

        $fec_ini = $startDate ->format('d-m-Y');
        $fec_fin = $endDate ->format('d-m-Y');

        // Obtener los datos para los gráficos
        $dailyProductionData = DB::table('egg_productions')
            ->selectRaw('DATE(created_at) as date, SUM(quantity) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();
    
        $dailyProductionPaginadoData = DB::table('egg_productions')
            ->selectRaw('DATE(created_at) as date, SUM(quantity) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->simplePaginate(5, ['*'], 'dailyPage');//->paginate( 5); // Cambia el número a la cantidad de resultados por página que desees
           
        $monthlyProductionData = DB::table('egg_productions')
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(quantity) as total')
            ->whereBetween('created_at', [$startDate->startOfYear(), $endDate->endOfYear()])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $monthlyProductionPaginadoData = DB::table('egg_productions')
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(quantity) as total')
            ->whereBetween('created_at', [$startDate->startOfYear(), $endDate->endOfYear()])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('year')
            ->orderBy('month')
            ->simplePaginate(5, ['*'], 'monthlyPage'); // Cambia el número a la cantidad de resultados por página que desees
 
        $yearlyProductionPaginadoData = DB::table('egg_productions')
            ->selectRaw('YEAR(created_at) as year, SUM(quantity) as total')
            ->whereBetween('created_at', [$startDate->startOfDecade(), $endDate->endOfDecade()])
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy('year')
            ->simplePaginate(5, ['*'], 'yearlyPage'); // Cambia el número a la cantidad de resultados por página que desees
           
        $yearlyProductionData = DB::table('egg_productions')
            ->selectRaw('YEAR(created_at) as year, SUM(quantity) as total')
            ->whereBetween('created_at', [$startDate->startOfDecade(), $endDate->endOfDecade()])
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy('year')
            ->get();

        // Devolver una vista con los resultados
        return view('reports.report_production', compact(
            'totalProduction', 
            'fec_ini', 
            'fec_fin', 
            'dailyProductionData',
            'dailyProductionPaginadoData', 
            'monthlyProductionData',
            'monthlyProductionPaginadoData', 
            'yearlyProductionData',
            'yearlyProductionPaginadoData', 
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
        $dailyExpensesPaginadoData = DB::table('expenses')
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->simplePaginate(5, ['*'], 'dailyPage');// Cambia el número a la cantidad de resultados por página que desees

        $dailyExpensesData = DB::table('expenses')
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        // Obtener los datos para los gráficos mensuales
        $monthlyExpensesPaginadoData = DB::table('expenses')
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(amount) as total')
            ->whereBetween('created_at', [$startDate->startOfYear(), $endDate->endOfYear()])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('year')
            ->orderBy('month')
            ->simplePaginate(5, ['*'], 'monthlyPage'); // Cambia el número a la cantidad de resultados por página que desees

        $monthlyExpensesData = DB::table('expenses')
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(amount) as total')
            ->whereBetween('created_at', [$startDate->startOfYear(), $endDate->endOfYear()])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Obtener los datos para los gráficos anuales
        $yearlyExpensesPaginadoData = DB::table('expenses')
            ->selectRaw('YEAR(created_at) as year, SUM(amount) as total')
            ->whereBetween('created_at', [$startDate->startOfDecade(), $endDate->endOfDecade()])
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy('year')
            ->simplePaginate(5, ['*'], 'yearlyPage'); // Cambia el número a la cantidad de resultados por página que desees

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
            'dailyExpensesPaginadoData', 
            'monthlyExpensesData', 
            'monthlyExpensesPaginadoData', 
            'yearlyExpensesData',
            'yearlyExpensesPaginadoData'
        ));
    }
}
