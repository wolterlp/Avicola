<x-app-layout>
    <!-- Encabezado del Dashboard -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 dark:text-pink-400 leading-tight">
            {{ __('Sales History') }}
        </h2>
    </x-slot>
    @if (session('success'))
        <div class="alert alert-success">
            @include('modal-alert')
        </div>
    @endif
    <!-- Contenido principal del Dashboard -->
    <div class="py-12">
        <div class="max-w-7x1 mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="form-container">
                    <form method="GET" action="{{ route('sales.history') }}" class="form-wrapper">
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
                                {{ __('Consultar Ventas') }}
                            </button>
                        </div>
                        <div class="flex justify-center">
                            <a>Ventas del {{ __($fec_ini) }} Al {{ __($fec_fin) }}</a>
                        </div>
                    </form>

                    @if (isset($fec_fin))
                        <div class="financial-summary">
                            <table class="financial-table">
                                <thead>
                                    <tr>
                                        <th class="descrip text-sm sm:text-lg">{{ __('Categoría') }}</th>
                                        <th class="descrip">{{ __('Cantidad Vendida') }}</th>
                                        <th class="descrip">{{ __('Precio por Unidad') }}</th>
                                        <th class="descrip">{{ __('Precio Total') }}</th>
                                        <th class="descrip">{{ __('Vendido por') }}</th>
                                        <th class="descrip">{{ __('Fecha') }}</th>
                                        <th class="descrip">{{ __('Detalles') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sales as $sale)
                                        <tr>
                                            <td class="textCenter">{{ $sale->eggCategory->category }}</td>
                                            <td class="textCenter">{{ $sale->quantity }}</td>
                                            <td class="textCenter">$ {{ number_format($sale->price_per_unit, 2, ',', '.') }}</td>
                                            <td class="textCenter">$ {{ number_format($sale->total_price, 2, ',', '.') }}</td>
                                            <td class="textCenter">{{ $sale->user->name }}</td>
                                            <td>{{ $sale->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                <div>
                                                    <!-- Include the modal content -->
                                                    @include('sales/modal-show')
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Enlaces de paginación -->
                        <div class="mt-4">
                            {{ $sales->appends(['start_date' => request('start_date'), 'end_date' => request('end_date')])->links() }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="mt-12"></div>
        </div>
    </div>
</x-app-layout>
