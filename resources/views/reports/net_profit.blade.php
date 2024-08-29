<x-app-layout>
    <!-- Encabezado del Dashboard -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 dark:text-pink-400 leading-tight">
            {{ __('Reporte de Utilidades Netas') }}
        </h2>
    </x-slot>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <!-- Contenido principal del Dashboard -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex justify-center items-center">
                    <div class="form-container">
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
                                        <th class="descrip">{{ __('Descripción') }}</th>
                                        <th class="descrip">{{ __('Monto') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ __('Ingresos Totales') }}</td>
                                        <td>${{ number_format($totalRevenue, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('Gastos Totales') }}</td>
                                        <td>${{ number_format($totalExpenses, 2) }}</td>
                                    </tr>
                                    <tr class="total-row">
                                        <td>{{ __('Utilidad Neta') }}</td>
                                        <td>${{ number_format($netProfit, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>