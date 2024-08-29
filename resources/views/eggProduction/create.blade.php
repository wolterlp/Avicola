<x-app-layout>
    <!-- Encabezado del Dashboard -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 dark:text-pink-400 leading-tight">
            {{ __('Registrar Producción de Huevos') }}
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
                        <form action="{{ route('eggProduction.store') }}" method="POST" class="form-wrapper">
                            @csrf

                            <div class="flex flex-col mb-4">
                                <label for="date" class="text-pink-500 font-semibold"> {{ __('Fecha:') }}</label>
                                <input type="date" id="date" name="date" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}" required readonly
                                    class="form-input">
                            </div>

                            <div class="flex flex-col mb-4">
                                <label for="quantity" class="text-pink-500 font-semibold"> {{ __('Cantidad:') }}</label>
                                <input type="number" id="quantity" name="quantity" required
                                    class="form-input">
                            </div>

                            <div class="flex flex-col mb-4">
                                <label for="egg_category_id" class="text-pink-500 font-semibold"> {{ __('Categoría de Huevo:') }}</label>
                                <select id="egg_category_id" name="egg_category_id" required
                                        class="form-input">
                                        <option value="" disabled selected> {{ __('Seleccione una categoría (obligatorio)') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->category }} - {{ $category->description }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Campo oculto para enviar el ID del usuario -->
                            <input type="hidden" name="user_id" value="{{ Auth::id() }}">

                            <div class="flex justify-center">
                                <button type="submit" class="form-button">
                                     {{ __('Registrar Producción') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
