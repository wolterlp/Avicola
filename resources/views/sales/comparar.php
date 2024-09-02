<!-- resources/views/sales/show.blade.php -->
<x-app-layout>
    <!-- Encabezado del Dashboard -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 dark:text-pink-400 leading-tight">
            {{ __('Detalles de la Venta') }}
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

                        <div class="container">

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"> {{ __('Categoría:') }} {{ $sale->eggCategory->name }}</h5>
                                    <p class="card-text"> {{ __('Descripción de Categoría:') }} {{ $sale->eggCategory->description }}</p>
                                    <p class="card-text"> {{ __('Vendido por:') }} {{ $sale->user->name }}</p>
                                    <p class="card-text"> {{ __('Cantidad Vendida:') }} {{ $sale->quantity }}</p>
                                    <p class="card-text"> {{ __('Precio por Unidad:') }} ${{ $sale->price_per_unit }}</p>
                                    <p class="card-text"> {{ __('Precio Total:') }} ${{ $sale->total_price }}</p>
                                </div>
                            </div>

                            <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Volver</a>
                        </div>

                        </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- resources/views/modal-show.blade.php -->
<div x-data="{ open: false }">
    <!-- Button to open the modal -->
    <button @click="open = true">Open Modal</button>

    <!-- Modal -->
    <div x-show="open" @click.away="open = false" class="modal">
        <div class="modal-show" @click.stop>
            <button class="close-btn" @click="open = false">&times;</button>
            <h2>Modal Title</h2>
            <p>This is a modal Show.</p>
            <div class="container">

<div class="card">
    <div class="card-body">
        <h5 class="card-title"> {{ __('Categoría:') }} {{ $sale->eggCategory->name }}</h5>
        <p class="card-text"> {{ __('Descripción de Categoría:') }} {{ $sale->eggCategory->description }}</p>
        <p class="card-text"> {{ __('Vendido por:') }} {{ $sale->user->name }}</p>
        <p class="card-text"> {{ __('Cantidad Vendida:') }} {{ $sale->quantity }}</p>
        <p class="card-text"> {{ __('Precio por Unidad:') }} ${{ $sale->price_per_unit }}</p>
        <p class="card-text"> {{ __('Precio Total:') }} ${{ $sale->total_price }}</p>
    </div>
</div>

<a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Volver</a>
</div>


            <button @click="open = false">Close Modal</button>
        </div>
    </div>
</div>
