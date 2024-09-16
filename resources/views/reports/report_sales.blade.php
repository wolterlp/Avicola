@push('scripts')
    <script src="{{ mix('js/app.js') }}"></script>
@endpush

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 dark:text-pink-400 leading-tight">
            {{ __('Sales Report') }}
        </h2>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success">
            @include('modal-alert')
        </div>
    @endif

    <div class="py-12">
        <!-- Contenedor principal con diseño responsivo -->
        <div class="max-w-7x1 mx-auto sm:px-6 lg:px-8">
            <!--div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"-->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Sección del formulario de reportes -->
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-center gap-4">
                    <div class="form-container w-full ">
                        <form method="GET" action="{{ route('report.reportSales') }}" class="form-wrapper">
                            @csrf
                            <div class="flex flex-col mb-4">
                                <label for="start_date" class="text-pink-500 font-semibold"> {{ __('Fecha de Inicio') }}:</label>
                                <input type="date" id="start_date" name="start_date" class="form-input" value="{{ request('start_date') }}" required>
                            </div>
                            <div class="flex flex-col mb-4">
                                <label for="end_date" class="text-pink-500 font-semibold"> {{ __('Fecha de Fin') }}:</label>
                                <input type="date" id="end_date" name="end_date" class="form-input" value="{{ request('end_date') }}" required>
                            </div>
                            <div class="flex justify-center">
                                <button type="submit" class="form-button">
                                    {{ __('Generar Reporte') }}
                                </button>
                            </div>
                            <div class="flex justify-center">    
                                <a>Ventas {{ __($fec_ini) }} Al {{ __($fec_fin) }}</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sección de resumen financiero y gráficos -->
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-center gap-4"-->
                    <!-- Ventas Diarias -->
                    <div class="form-container w-full mx-auto">
                        <h3 class="font-semibold text-lg text-pink-600">{{ __('Ventas Diarias') }}</h3>
                        <div class="overflow-x-auto">
                            <table class="financial-table ">
                                <thead>
                                    <tr>
                                        <th>{{ __('Fecha') }}</th>
                                        <th>{{ __('Categoría') }}</th>
                                        <th>{{ __('Total Huevos') }}</th>
                                        <th>{{ __('Ventas Totales') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dailyPaginadoSalesData as $sale)
                                        <tr>
                                            <td>{{ $sale->date }}</td>
                                            <td>{{ $sale->category }}</td>
                                            <td>{{ $sale->total_eggs }}</td>
                                            <td>$ {{ number_format($sale->total, 2, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>  
                              
                        </div>
                        <div class="mt-2">
                                {{ $dailyPaginadoSalesData
                                    ->appends(['start_date' => request('start_date'), 'end_date' => request('end_date')])
                                    ->links('', ['paginator' => $dailyPaginadoSalesData]) }}         
                            </div>
                        <div class="mt-12">
                            <canvas id="dailySalesChart" width="300" height="300" ></canvas>
                        </div>
                    </div>
                </div>    

                <div class=" contenedor1 p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex flex-col mb-11 sm:flex-row justify-center gap-4">
                    <!-- Ventas Mensuales -->
                    <div class="contenedor2 flex flex-col mb-4 w-full sm:w-1/2 max-w-4xl mx-auto">
                        <h3 class="font-semibold text-lg text-pink-600">{{ __('Ventas Mensuales') }}</h3>
                        <div class="contenedor3 overflow-x-auto financial-summary p-6">
                            <table class="financial-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Año') }}</th>
                                        <th>{{ __('Mes') }}</th>
                                        <th>{{ __('Total Huevos') }}</th>
                                        <th>{{ __('Ventas Totales') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($monthlyPaginadoSalesData as $sale)
                                        <tr>
                                            <td>{{ $sale->year }}</td>
                                            <td>{{ $sale->month }}</td>
                                            <td>{{ $sale->total_eggs }}</td>
                                            <td>$ {{ number_format($sale->total, 2, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-2">
                            {{ $monthlyPaginadoSalesData
                                ->appends(['start_date' => request('start_date'), 'end_date' => request('end_date')])
                                ->links('', ['paginator' => $monthlyPaginadoSalesData]) }}
                        </div>
                        <div class="contenedor4">
                            <canvas id="monthlySalesChart"></canvas>
                        </div>
                    </div>

                    <!-- Ventas Anuales -->
                    <div class="contenedor2 flex flex-col mb-4 w-full sm:w-1/2  mx-auto">
                        <h3 class="font-semibold text-lg text-pink-600">{{ __('Ventas Anuales') }}</h3>
                        <div class="contenedor3 overflow-x-auto financial-summary">
                            <table class="financial-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Año') }}</th>
                                        <th>{{ __('Total Huevos') }}</th>
                                        <th>{{ __('Ventas Totales') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($yearlyPaginadoSalesData as $sale)
                                        <tr>
                                            <td>{{ $sale->year }}</td>
                                            <td>{{ $sale->total_eggs }}</td>
                                            <td>$ {{ number_format($sale->total, 2, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    <div class="mt-2">
                            {{ $yearlyPaginadoSalesData
                                ->appends(['start_date' => request('start_date'), 'end_date' => request('end_date')])
                                ->links('', ['paginator' => $yearlyPaginadoSalesData]) }}
                    </div>
                    <div class="contenedor4">
                        <canvas id="yearlySalesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts JSON con Datos -->
    <script id="dailySalesData" type="application/json">
        @json($dailySalesData)
    </script>
    <script id="monthlySalesData" type="application/json">
        @json($monthlySalesData)
    </script>
    <script id="yearlySalesData" type="application/json">
        @json($yearlySalesData)
    </script>
    <script id="totalRevenue" type="application/json">
        @json($totalRevenue)
    </script>
    <script id="totalExpenses" type="application/json">
        @json($totalExpenses)
    </script>
    <script id="netProfit" type="application/json">
        @json($netProfit)
    </script>

</x-app-layout>
