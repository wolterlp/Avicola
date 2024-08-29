{{-- resources/views/eggProduction/inventario.blade.php --}}
<x-app-layout>
    <!-- Encabezado del Dashboard -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 dark:text-pink-400 leading-tight">
            {{ __('Egg inventory') }}
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
                        @if (session('success'))
                            <div class="alert alert-success mb-4 p-4 bg-green-100 text-green-700 border border-green-300 rounded-lg">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="financial-summary">
                            <!--table class="min-w-full divide-y divide-gray-200 border border-gray-300"-->
                            <table class="financial-table">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="descrip">{{ __('Categoría') }}</th>
                                        <th class="descrip">{{ __('Descripción') }}</th>
                                        <th class="descrip">{{ __('Cantidad Producida') }}</th>
                                        <th class="descrip">{{ __('Cantidad Vendida') }}</th>
                                        <th class="descrip">{{ __('Stock Actual') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($inventory as $item)
                                        <tr>
                                            <td>{{ $item->category }}</td>
                                            <td>{{ $item->description }}</td>
                                            <td class="textCenter">{{ $item->total_produced }}</td>
                                            <td class="textCenter">{{ $item->total_sold }}</td>
                                            <td class="textCenter">{{ $item->total_inventory }}</td>
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