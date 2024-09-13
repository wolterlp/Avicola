@push('scripts')
    <script src="{{ mix('js/app.js') }}"></script>
@endpush

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 dark:text-pink-400 leading-tight">
            {{ __('Production Report') }}
        </h2>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success">
            @include('modal-alert')
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7x1 mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Sección del formulario de reportes -->
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-center items-center gap-4">
                    <div class="form-container w-full ">
                        <form method="GET" action="{{ route('report.reportProduction') }}" class="form-wrapper">
                            @csrf
                            <div class="flex flex-col mb-4">
                                <label for="start_date" class="text-pink-500 font-semibold" >{{ __('Fecha de Inicio') }}:</label>
                                <input type="date" id="start_date" name="start_date" class="bg-transparent form-input" value="{{ request('start_date') }}" required>
                            </div>
                            <div class="flex flex-col mb-4">
                                <label for="end_date" class="text-pink-500 font-semibold">{{ __('Fecha de Fin') }}:</label>
                                <input type="date" id="end_date" name="end_date" class="form-input" value="{{ request('end_date') }}" required>
                            </div>
                            <div class="flex justify-center">
                                <button type="submit" class="form-button">
                                    {{ __('Generar Reporte') }}
                                </button>
                            </div>
                            <div class="flex justify-center">    
                                <a>Produccion de huevos del {{ __($fec_ini) }} Al {{ __($fec_fin) }}</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Reporte de Producción -->
                
                @if (isset($fec_ini) && isset($fec_fin))
                    <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-center items-center gap-4">
                        <!-- Producción Diaria -->
                        <div class="form-container w-full sm:w-1/2">
                            
                            <h3 class="font-semibold text-lg text-pink-600">{{ __('Producción Diaria') }}</h3>
                            <table class="financial-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Fecha') }}</th>
                                        <th>{{ __('Producción Total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dailyProductionData as $production)
                                        <tr>
                                            <td>{{ $production->date }}</td>
                                            <td>{{ $production->total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-12">
                                <canvas id="dailyProductionChart" width="300" height="300"></canvas>
                            </div>
                        </div>
                        <!-- Producción Mensual -->
                        <div class="form-container w-full sm:w-1/2">
                            <h3 class="font-semibold text-lg text-pink-600">{{ __('Producción Mensual') }}</h3>
                            <table class="financial-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Año') }}</th>
                                        <th>{{ __('Mes') }}</th>
                                        <th>{{ __('Producción Total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($monthlyProductionData as $production)
                                        <tr>
                                            <td>{{ $production->year }}</td>
                                            <td>{{ $production->month }}</td>
                                            <td>{{ $production->total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-12">
                                <canvas id="monthlyProductionChart" width="300" height="300"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Producción Anual -->
                    <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-center items-center gap-4">
                        <div class="form-container w-full sm:w-1/2">
                            <h3 class="font-semibold text-lg text-pink-600">{{ __('Producción Anual') }}</h3>
                            <table class="financial-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Año') }}</th>
                                        <th>{{ __('Producción Total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($yearlyProductionData as $production)
                                        <tr>
                                            <td>{{ $production->year }}</td>
                                            <td>{{ $production->total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-12">
                                <canvas id="yearlyProductionChart" width="300" height="300"></canvas>
                            </div>
                        </div>
                        <!-- Producción por Categoría -->
                        <div class="form-container w-full sm:w-1/2">
                            <h3 class="font-semibold text-lg text-pink-600">{{ __('Producción por Categoría') }}</h3>
                            <table class="financial-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Categoría') }}</th>
                                        <th>{{ __('Producción Total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($productionByCategory as $category)
                                        <tr>
                                            <td>{{ $category->category }}</td>
                                            <td>{{ $category->total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-12">
                                <canvas id="categoryProductionChart" width="300" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Scripts JSON con Datos -->
    <script id="dailyProductionData" type="application/json">
        @json($dailyProductionData)
    </script>
    <script id="monthlyProductionData" type="application/json">
        @json($monthlyProductionData)
    </script>
    <script id="yearlyProductionData" type="application/json">
        @json($yearlyProductionData)
    </script>
    <script id="productionByCategory" type="application/json">
        @json($productionByCategory)
    </script>

</x-app-layout>
