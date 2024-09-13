@push('scripts')
    <script src="{{ mix('js/app.js') }}"></script>
@endpush

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 dark:text-pink-400 leading-tight">
            {{ __('Expenses Report') }}
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
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"> 
                <!-- Sección del formulario de reportes -->
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-center items-center gap-4">
                    <div class="form-container w-full p-6 ">
                        <form method="GET" action="{{ route('report.reportSales') }}" class="form-wrapper">
                            @csrf    
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
                            <div class="flex justify-center">    
                                <a>Gatos del {{ __($fec_ini) }} Al {{ __($fec_fin) }}</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sección de resumen financiero y gráficos -->
                    <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-center items-center gap-4"-->
                        <!-- Sección de Gastos diarias, mensuales y anuales -->
                        <!-- Gastos Diarias -->
                        <div class="form-container w-full mx-auto">
                            <h3 class="font-semibold text-lg text-pink-600">{{ __('Gastos Diarias') }}</h3>
                            <table class="financial-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Fecha') }}</th>
                                        <th>{{ __('Gastos Totales') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dailyExpensesData as $sale)
                                        <tr>
                                            <td>{{ $sale->date }}</td>
                                            <td>$ {{ number_format($sale->total, 2, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-12">
                                <canvas id="dailyExpensesChart" width="300" height="300"></canvas>
                            </div>
                        </div>
                    </div>    
                    
                    <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-center items-center gap-4">
                        <!-- Gastos Mensuales -->
                        <div class="form-container w-full sm:w-1/2">
                            <h3 class="font-semibold text-lg text-pink-600">{{ __('Gastos Mensuales') }}</h3>
                            <table class="financial-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Año') }}</th>
                                        <th>{{ __('Mes') }}</th>
                                        <th>{{ __('Gastos Totales') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($monthlyExpensesData as $sale)
                                        <tr>
                                            <td>{{ $sale->year }}</td>
                                            <td>{{ $sale->month }}</td>
                                            <td>$ {{ number_format($sale->total, 2, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-12">
                                <canvas id="monthlyExpensesChart" width="300" height="300"></canvas>
                            </div>
                        </div>

                        <!-- Gastos Anuales -->
                        <div class="form-container w-full sm:w-1/2">
                            <h3 class="font-semibold text-lg text-pink-600">{{ __('Gastos Anuales') }}</h3>
                            <table class="financial-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Año') }}</th>
                                        <th>{{ __('Gastos Totales') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($yearlyExpensesData as $sale)
                                        <tr>
                                            <td>{{ $sale->year }}</td>
                                            <td>$ {{ number_format($sale->total, 2, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-12">
                                <canvas id="yearlyExpensesChart" width="300" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts JSON con Datos -->
    <script id="dailyExpensesData" type="application/json">
        @json($dailyExpensesData)
    </script>
    <script id="monthlyExpensesData" type="application/json">
        @json($monthlyExpensesData)
    </script>
    <script id="yearlyExpensesData" type="application/json">
        @json($yearlyExpensesData)
    </script>

</x-app-layout>
