import './bootstrap';
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('loadModalContent').addEventListener('click', function () {
        fetch('/modal-content')
            .then(response => response.text())
            .then(data => {
                document.querySelector('#exampleModal .modal-body').innerHTML = data;
            });
    });
});

//modales
function openModal(url) {
    fetch(url)
        .then(response => response.text())
        .then(html => {
            document.getElementById('modal-container').innerHTML = html;
            document.getElementById('modal').style.display = 'block';
        });
}

function closeModal() {
    document.getElementById('modal').style.display = 'none';
}