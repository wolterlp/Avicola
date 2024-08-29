
<x-app-layout>
    <!-- Encabezado del Dashboard -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 dark:text-pink-400 leading-tight">
            {{ __('Registrar Gasto') }}
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
                        <form action="{{ route('expenses.store') }}" method="POST" class="form-wrapper">
                            @csrf

                            <div class="flex flex-col mb-4">
                                <label for="description" class="text-pink-500 font-semibold">{{ __('Descripción') }}:</label>
                                <input type="text" id="description" name="description" class="form-input" required>
                            </div>

                            <div class="flex flex-col mb-4">
                                <label for="amount" class="text-pink-500 font-semibold">{{ __('Monto') }}:</label>
                                <input type="number" id="amount" name="amount" class="form-input" step="0.01" required>
                            </div>

                            <div class="flex flex-col mb-4">
                                <label for="expense_date" class="text-pink-500 font-semibold">{{ __('Fecha') }}:</label>
                                <input type="date" id="expense_date" name="expense_date" class="form-input" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}" required readonly>
                            </div>

                            <div class="flex justify-center">
                                <button type="submit" class="form-button">
                                {{ __('Registrar') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>