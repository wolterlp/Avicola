<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Producción de Huevos</title>
</head>
<body>
    <h1>Registrar Producción de Huevos</h1>

    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <form action="{{ route('eggProduction.store') }}" method="POST">
        @csrf
        <label for="date">Fecha:</label>
        <input type="date" id="date" name="date" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}" required readonly>
        <br><br>

        <label for="quantity">Cantidad:</label>
        <input type="number" id="quantity" name="quantity" required>
        <br><br>

        <label for="egg_category_id">Categoría de Huevo:</label>
        <select id="egg_category_id" name="egg_category_id" required>
            @foreach($categories as $category)
                <!--option value="{{ $category->id }}">{{ $category->category }}</option-->
                <option value="{{ $category->id }}">{{ $category->category }} - {{ $category->description }}</option>
            @endforeach
        </select>
        <br><br>

        <!-- Campo oculto para enviar el ID del usuario -->
        <input type="hidden" name="user_id" value="{{ Auth::id() }}">

        <button type="submit">Registrar Producción</button>
    </form>
</body>
</html>
