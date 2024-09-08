<x-app-layout>
    <!-- Encabezado del Dashboard -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 dark:text-pink-400 leading-tight">
            {{ __('Ingresos por Venta de Huevos') }}
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
            <div class="form-container">
                <!-- Mostrar mensajes de error -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <!-- Formulario para seleccionar el rango de fechas -->
                <form action="{{ route('sales.revenue.calculate') }}" method="POST"  class="form-wrapper">
                    @csrf
                    <div class="flex flex-col mb-4">
                        <label for="start_date" class="text-pink-500 font-semibold">{{ __('Fecha de Inicio') }}:</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="flex flex-col mb-4">
                        <label for="end_date" class="text-pink-500 font-semibold">{{ __('Fecha de Fin') }}:</label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>
                    <div class="flex justify-center">
                        <button type="submit" class="form-button">
                                {{ __('Calcular Ingresos') }}
                        </button>
                    </div>
                </form>
                <!-- Mostrar los resultados -->
                @if (isset($totalRevenue))
                    <div class="flex justify-center mt-4">
                        <h3 class="text-pink-500 font-semibold">Ingresos Totales desde {{ $startDate }} hasta {{ $endDate }}</h3>
                    </div>    
                    <div class="flex justify-center mt-4">    
                        <h2 class="white-text font-semibold"><strong>${{ number_format($totalRevenue, 2) }}</strong></h2>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
