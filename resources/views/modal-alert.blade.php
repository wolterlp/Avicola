<!-- resources/views/modal-alert.blade.php -->
<div x-data="{ open: true }">
    <div x-show="open" @click.away="open = true" class="modal">
        <div class="modal-content" @click.stop>
            <button class="close-btn" @click="open = false">&times;</button>
                <div class="container">
                    <!-- resources/views/modal-show.blade.php -->
                    <br>
                        <h2 style=" color: #ec4899; font-weight: bold;" >{{ __('Notifications') }}</h2>
                        <p><strong>{{ __('Success') }}:</strong> {{ session('success') }}</p>
                    <br>
                </div>    
                <br>
                <br>
                <br>
            <button @click="open = false">{{ __('Cerrar') }}</button>
        </div>
    </div>
</div>



