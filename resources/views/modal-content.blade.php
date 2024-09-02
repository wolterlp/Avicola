<!-- resources/views/modal-content.blade.php -->
<div x-data="{ open: false }">
    <!-- Button to open the modal -->
    <button @click="open = true">Open Modal content</button>

    <!-- Modal -->
    <div x-show="open" @click.away="open = false" class="modal">
        <div class="modal-content" @click.stop>
            <button class="close-btn" @click="open = false">&times;</button>
            <h2>Modal content</h2>
            <p>This is a modal content.</p>
            <button @click="open = false"></button>
        </div>
    </div>
</div>

