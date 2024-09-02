<!-- resources/views/modal-show.blade.php -->
<div x-data="{ open: false }">
    <!-- Button to open the modal -->
    <button @click="open = true">Ver Detalles de la Venta</button>

    <!-- Modal -->
    <div x-show="open" @click.away="open = false" class="modal">
        <div class="modal-content" @click.stop>
            <button class="close-btn" @click="open = false">&times;</button>
                <div class="container">
                    <!-- resources/views/modal-show.blade.php -->
                    <br>
                        <h2 style=" color: #ec4899; font-weight: bold;" >Detalles de la Venta</h2>
                        <p><strong>Categoría:</strong> {{ $sale->eggCategory->category }}</p>
                        <p><strong>Cantidad Vendida:</strong> {{ $sale->quantity }}</p>
                        <p><strong>Precio por Unidad:</strong> $ {{ number_format($sale->price_per_unit , 2, ',', '.') }}</p>
                        <p><strong>Precio Total:</strong>$ {{ number_format($sale->total_price , 2, ',', '.') }}</p>
                        <p><strong>Vendido por:</strong> {{ $sale->user->name }}</p>
                        <p><strong>Fecha:</strong> {{ $sale->created_at->format('d/m/Y') }}</p>
                    <br>
                </div>    
                <br>
                <br>
                <br>
            <button @click="open = false">Cerrar</button>
        </div>
    </div>
</div>



