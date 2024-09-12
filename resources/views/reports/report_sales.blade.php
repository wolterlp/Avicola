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
        <div class="mx-auto max-w-7xl p-2 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sm:px-6 lg:px-8">
            
            <!-- Sección del formulario de reportes -->
            <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-center items-center gap-4">
                <div class="form-container w-full ">
                    <form method="GET" action="{{ route('report.reportSales') }}" class="form-wrapper">
                        <div class="flex flex-col mb-4">
                            <label for="start_date" class="text-pink-500 font-semibold"> {{ __('Fecha de Inicio') }}:</label>
                            <input type="date" id="start_date" name="start_date" class="form-input" required>
                        </div>
                        <div class="flex flex-col mb-4">
                            <label for="end_date" class="text-pink-500 font-semibold"> {{ __('Fecha de Fin') }}:</label>
                            <input type="date" id="end_date" name="end_date" class="form-input" required>
                        </div>
                        <div class="flex justify-center">
                            <button type="submit" class="form-button">
                                {{ __('Generar Reporte') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sección de resumen financiero y gráficos -->
            @if (isset($fec_ini) && isset($fec_fin))
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-center items-center gap-4">
                    <!-- Resumen financiero -->
                    <div class="form-container w-full sm:w-1/2">
                        <h3 class="font-semibold text-lg text-pink-600">{{ __('Informe Financiero') }}</h3>
                        <table class="financial-table">
                            <thead>
                                <tr>
                                    <th colspan="2">Reporte Del {{ $fec_ini }} Al {{ $fec_fin }} </th>
                                </tr>
                                <tr>
                                    <th class="descrip">{{ __('Descripción') }}</th>
                                    <th class="descrip">{{ __('Monto') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ __('Ingresos Totales') }}</td>
                                    <td>$ {{ number_format($totalRevenue, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Gastos Totales') }}</td>
                                    <td>$ {{ number_format($totalExpenses, 2, ',', '.') }}</td>
                                </tr>
                                <tr class="total-row">
                                    <td>{{ __('Utilidad Neta') }}</td>
                                    <td>$ {{ number_format($netProfit, 2, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Selección de tipo de gráfico y gráfico dinámico -->
                        <select id="chartTypeSelector" class="form-select mb-4 mt-12">
                            <option value="bar">{{ __('Bar') }}</option>
                            <option value="pie">{{ __('Pie') }}</option>
                            <option value="line">{{ __('Line') }}</option>
                            <option value="radar">{{ __('Radar') }}</option>
                            <option value="polarArea">{{ __('Polar Area') }}</option>
                            <option value="scatter">{{ __('Scatter') }}</option>
                            <option value="doughnut">{{ __('Doughnut') }}</option>
                        </select>
                        <div>
                            <canvas id="dynamicChart" width="300" height="300"></canvas>
                        </div>
                    </div>
                
            @endif

                    <!-- Sección de ventas diarias, mensuales y anuales -->
                    <!-- Ventas Diarias -->
                    <div class="form-container w-full sm:w-1/2">
                        <h3 class="font-semibold text-lg text-pink-600">{{ __('Ventas Diarias') }}</h3>
                        <table class="financial-table">
                            <thead>
                                <tr>
                                    <th>{{ __('Fecha') }}</th>
                                    <th>{{ __('Ventas Totales') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dailySalesData as $sale)
                                    <tr>
                                        <td>{{ $sale->date }}</td>
                                        <td>$ {{ number_format($sale->total, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-12">
                            <canvas id="dailySalesChart" width="300" height="300"></canvas>
                        </div>
                    </div>
                </div>    

                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-center items-center gap-4">
                    <!-- Ventas Mensuales -->
                    <div class="form-container w-full sm:w-1/2">
                        <h3 class="font-semibold text-lg text-pink-600">{{ __('Ventas Mensuales') }}</h3>
                        <table class="financial-table">
                            <thead>
                                <tr>
                                    <th>{{ __('Año') }}</th>
                                    <th>{{ __('Mes') }}</th>
                                    <th>{{ __('Ventas Totales') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($monthlySalesData as $sale)
                                    <tr>
                                        <td>{{ $sale->year }}</td>
                                        <td>{{ $sale->month }}</td>
                                        <td>$ {{ number_format($sale->total, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-12">
                            <canvas id="monthlySalesChart" width="300" height="300"></canvas>
                        </div>
                    </div>

                    <!-- Ventas Anuales -->
                    <div class="form-container w-full sm:w-1/2">
                        <h3 class="font-semibold text-lg text-pink-600">{{ __('Ventas Anuales') }}</h3>
                        <table class="financial-table">
                            <thead>
                                <tr>
                                    <th>{{ __('Año') }}</th>
                                    <th>{{ __('Ventas Totales') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($yearlySalesData as $sale)
                                    <tr>
                                        <td>{{ $sale->year }}</td>
                                        <td>$ {{ number_format($sale->total, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-12">
                            <canvas id="yearlySalesChart" width="300" height="300"></canvas>
                        </div>
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
