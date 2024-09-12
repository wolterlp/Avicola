@push('scripts')
    <script src="{{ mix('js/app.js') }}"></script>
@endpush

<!-- Ocultar los valores que se necesitan en app.js -->
<input type="hidden" id="totalRevenue" value="{{ $totalRevenue }}">
<input type="hidden" id="totalExpenses" value="{{ $totalExpenses }}">
<input type="hidden" id="netProfit" value="{{ $netProfit }}">

<x-app-layout>
    <!-- Encabezado del Dashboard -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 dark:text-pink-400 leading-tight">
            {{ __('Reporte de Utilidades Netas') }}
        </h2>
    </x-slot>
    @if (session('success'))
        <div class="alert alert-success">
            @include('modal-alert')
        </div>
    @endif
    <!-- Contenido principal del Dashboard -->
    <div class="py-12">
        <div class="mx-auto max-w-7xl p-2 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700  sm:px-6 lg:px-8">
            <!-- Flex container -->
            <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-center items-center gap-4">
                <!-- Primer div form-container -->
                <div class="form-container  w-full sm:w-1/2">
                    <form method="GET" action="{{ route('report.netProfit') }}" class="form-wrapper">
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
                    @if (isset($netProfit))
                    <div class="financial-summary">
                        <table class="financial-table">
                            <thead>
                                <tr>
                                    <th colspan="2">Reporte Del {{ __($fec_ini) }} Al {{ __($fec_fin) }} </th>
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
                    </div>
                    @endif
                </div>
                <!-- Segundo div form-container -->
                <div class="form-container w-full sm:w-1/2" style="margin: 5px auto;">
                    <h2 class="font-semibold text-xl text-pink-600 dark:text-pink-500 leading-tight text-align:center">{{ __('Informe Financiero') }}</h2>
                    <select id="chartTypeSelector" class="form-select mb-4">
                        <option value="bar">{{ __('Bar') }}</option>
                        <option value="pie">{{ __('Pie') }}</option>
                        <option value="line">{{ __('Line') }}</option>
                        <option value="radar">{{ __('Radar') }}</option>
                        <option value="polarArea">{{ __('Polar Area') }}</option>
                        <option value="scatter">{{ __('Scatter') }}</option>
                        <option value="doughnut">{{ __('Doughnut') }}</option>
                    </select>
                    <div>
                        <canvas id="dynamicChart" width="500" height="500"></canvas>
                       
                    </div>
                 </div>
            </div>
        </div>
    </div>
</x-app-layout>