<x-app-layout>
    <!-- Encabezado del Dashboard -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 dark:text-pink-400 leading-tight">
        {{ __('Registrar Venta') }}
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
                    <form action="{{ route('sales.store') }}" method="POST" class="form-wrapper">
                        @csrf
                        <div class="flex flex-col mb-4">
                            <label for="egg_category_id" class="text-pink-500 font-semibold">{{ __('Categoría de Huevo') }}</label>
                            <select id="egg_category_id" name="egg_category_id"class="form-input" required>
                                <option value="" disabled selected> {{ __('Seleccione una categoría (obligatorio)') }}</option>
                                @foreach ($eggCategories as $category)
                                    <option  value="{{ $category->id }}">{{ $category->category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col mb-4">
                            <label for="quantity" class="text-pink-500 font-semibold">{{ __('Cantidad') }}</label>
                            <input type="number" id="quantity" name="quantity" class="form-input" required>
                        </div>
                        <div class="flex flex-col mb-4">
                            <label for="price_per_unit" class="text-pink-500 font-semibold">{{ __('Precio por Unidad') }}</label>
                            <input type="number" step="0.01" id="price_per_unit" name="price_per_unit" class="form-input" required>
                        </div>
                        <div class="flex flex-col mb-4">
                            <label for="panal" class="text-pink-500 font-semibold">{{ __('Panales') }}</label>
                            <input type="text" step="0.01" id="panal" name="panal" class="form-input" readonly>
                        </div>
                        <div class="flex justify-center">
                            <button type="submit" class="form-button">
                                {{ __('Registrar Venta') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="mt-12 "></div>
        </div>
    </div>
</x-app-layout>
