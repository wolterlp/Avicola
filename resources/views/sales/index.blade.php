<x-app-layout>
    <!-- Encabezado del Dashboard -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 dark:text-pink-400 leading-tight">
            {{ __('Sales') }}
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
                        <div class="flex justify-center">
                            <a href="{{ route('sales.create') }}" class="btn btn-primary mb-3 form-button">
                                {{ __('Registrar Nueva Venta') }}
                            </a>
                        </div>
                        <div class="financial-summary">
                            <table class="financial-table">
                                <thead>
                                    <tr>
                                        <th class="descrip">{{ __('Categoría') }}</th>
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
                                            <td class="textCenter">$ {{ number_format($sale->price_per_unit, 2, ',', '.') }} </td>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>    
