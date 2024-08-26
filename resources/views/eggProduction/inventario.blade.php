<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario de Huevos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Inventario de Huevos</h1>

    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Categoría</th>
                <th>Descripción Categoría</th>
                <th>Total de Huevos</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventario as $item)
                <tr>
                    <td>{{ $item->eggCategory->category }}</td>
                    <td>{{ $item->eggCategory->description }}</td>
                    <td>{{ $item->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
