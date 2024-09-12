import './bootstrap';
import Alpine from 'alpinejs';
import './chart.js';

window.Alpine = Alpine;

Alpine.start();

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
//fin modales

/* inicio menu*/
    document.addEventListener('DOMContentLoaded', function() {
        const menuTrigger = document.querySelector('.group');
        menuTrigger.addEventListener('click', function(event) {
            event.stopPropagation();
            const dropdown = this.querySelector('.group-hover\\:block');
            dropdown.classList.toggle('hidden');
        });

        // Cierra el dropdown al hacer clic fuera de él
        document.addEventListener('click', function() {
            const dropdowns = document.querySelectorAll('.group-hover\\:block');
            dropdowns.forEach(function(dropdown) {
                dropdown.classList.add('hidden');
            });
        });
    });
/*fin menu*/

/*inicio graficos*/
    // Función para crear y actualizar el gráfico
    function createAndUpdateChart() {
        var ctx = document.getElementById('dynamicChart').getContext('2d');

        // Datos del gráfico
        var chartData = {
            labels: ['Ingresos', 'Gastos', 'Utilidad Neta'],
            datasets: [{
                label: 'Monto en $',
                data: [document.getElementById('totalRevenue').value, document.getElementById('totalExpenses').value, document.getElementById('netProfit').value],
                backgroundColor: ['#4caf50', '#f44336', '#2196f3'],
                borderColor: ['#388e3c', '#d32f2f', '#1976d2'],
                borderWidth: 1
            }]
        };

        // Opciones del gráfico
        var chartOptions = {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        };

        // Crear el gráfico inicialmente como un gráfico de barras
        var dynamicChart = new Chart(ctx, {
            type: 'bar', // Tipo de gráfico por defecto
            data: chartData,
            options: chartOptions
        });

        // Función para actualizar el gráfico
        function updateChartType(newType) {
            dynamicChart.destroy(); // Destruir el gráfico anterior
            dynamicChart = new Chart(ctx, {
                type: newType, // Nuevo tipo de gráfico seleccionado
                data: chartData,
                options: chartOptions
            });
        }

        // Escuchar el cambio en el select
        document.getElementById('chartTypeSelector').addEventListener('change', function() {
            var selectedType = this.value;
            updateChartType(selectedType); // Actualizar el gráfico con el nuevo tipo
        });
    }

// Ejecutar la función para crear y actualizar el gráfico después de que el DOM esté completamente cargado
//document.addEventListener('DOMContentLoaded', createAndUpdateChart);

//reporte sales 


function createReportSalesCharts() {
    var ctxDaily = document.getElementById('dailySalesChart').getContext('2d');
    var ctxMonthly = document.getElementById('monthlySalesChart').getContext('2d');
    var ctxYearly = document.getElementById('yearlySalesChart').getContext('2d');
    /*
    var ctxTotalRevenue = document.getElementById('totalRevenueChart').getContext('2d');
    var ctxExpenses = document.getElementById('totalExpensesChart').getContext('2d');
    var ctxnetProfit = document.getElementById('netProfitChart').getContext('2d');
    */
    // Obtener los datos de ventas desde los scripts JSON
    const dailySalesData = JSON.parse(document.getElementById('dailySalesData').textContent);
    const monthlySalesData = JSON.parse(document.getElementById('monthlySalesData').textContent);
    const yearlySalesData = JSON.parse(document.getElementById('yearlySalesData').textContent);
/*
    // Obtener los datos de ventas desde los scripts JSON
    const totalRevenue = JSON.parse(document.getElementById('totalRevenueData').textContent);
    const totalExpenses = JSON.parse(document.getElementById('totalExpensesData').textContent);
    const netProfit = JSON.parse(document.getElementById('netProfitData').textContent)
*/
    // Imprime los datos en la consola
    /*
    console.log('Daily Sales Data:', dailySalesData);
    console.log('Monthly Sales Data:', monthlySalesData);
    console.log('Yearly Sales Data:', yearlySalesData);
    */

    // Transformar los datos para los gráficos
    const dailyLabels = dailySalesData.map(data => data.date);
    const dailyValues = dailySalesData.map(data => parseFloat(data.total));

    const monthlyLabels = monthlySalesData.map(data => data.month); // Asumiendo el formato correcto
    const monthlyValues = monthlySalesData.map(data => parseFloat(data.total)); // Asumiendo el formato correcto

    const yearlyLabels = yearlySalesData.map(data => data.year); // Asumiendo el formato correcto
    const yearlyValues = yearlySalesData.map(data => parseFloat(data.total)); // Asumiendo el formato correcto

    // Configuración de los gráficos
    var dailyChartData = {
        labels: dailyLabels,
        datasets: [{
            label: 'Ventas Diarias',
            data: dailyValues,
            backgroundColor: '#4caf50',
            borderColor: '#388e3c',
            borderWidth: 1
        }]
    };

    var monthlyChartData = {
        labels: monthlyLabels,
        datasets: [{
            label: 'Ventas Mensuales',
            data: monthlyValues,
            backgroundColor: ['#4caf50', '#f44336', '#2196f3'],
            borderColor: ['#388e3c', '#d32f2f', '#1976d2'],
            borderWidth: 1
        }]
    };

    var yearlyChartData = {
        labels: yearlyLabels,
        datasets: [{
            label: 'Ventas Anuales',
            data: yearlyValues,
            backgroundColor: '#2196f3',
            borderColor: '#1976d2',
            borderWidth: 1
        }]
    };

    var chartOptions = {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    };

    // Crear los gráficos
    new Chart(ctxDaily, {
        type: 'line',
        data: dailyChartData,
        options: chartOptions
    });

    new Chart(ctxMonthly, {
        type: 'bar',
        data: monthlyChartData,
        options: chartOptions
    });

    new Chart(ctxYearly, {
        type: 'bar',
        data: yearlyChartData,
        options: chartOptions
    });
}

// Ejecutar la función para crear y actualizar los gráficos después de que el DOM esté completamente cargado
//document.addEventListener('DOMContentLoaded', createReportSalesCharts);

//fin reporte sales 

//reporte produccion

function creatReportProductionCharts() {

//document.addEventListener('DOMContentLoaded', () => {
    // Obtén los datos desde los scripts JSON
    const dailyData = JSON.parse(document.getElementById('dailyProductionData').textContent);
    const monthlyData = JSON.parse(document.getElementById('monthlyProductionData').textContent);
    const yearlyData = JSON.parse(document.getElementById('yearlyProductionData').textContent);
    const categoryData = JSON.parse(document.getElementById('productionByCategory').textContent);

    // Configura los contextos para los gráficos
    const ctxDaily = document.getElementById('dailyProductionChart').getContext('2d');
    const ctxMonthly = document.getElementById('monthlyProductionChart').getContext('2d');
    const ctxYearly = document.getElementById('yearlyProductionChart').getContext('2d');
    const ctxCategory = document.getElementById('categoryProductionChart').getContext('2d');

    // Inicializa los gráficos
    const dailyChart = new Chart(ctxDaily, {
        type: 'line', // Tipo inicial del gráfico
        data: {
            labels: dailyData.map(d => d.date),
            datasets: [{
                label: 'Producción Diaria',
                data: dailyData.map(d => d.total),
                borderColor: 'rgba(255, 99, 132, 1)', // Rosa
                backgroundColor: 'rgba(75, 192, 192, 0.2)', // Verde claro
                borderWidth: 2, // Ancho del borde
                fill: false, // No rellenar el área debajo de la línea
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const monthlyChart = new Chart(ctxMonthly, {
        type: 'bar', // Tipo inicial del gráfico
        data: {
            labels: monthlyData.map(d => `${d.month}/${d.year}`),
            datasets: [{
                label: 'Producción Mensual',
                data: monthlyData.map(d => d.total),
                borderColor: 'rgba(255, 99, 132, 1)', // Rosa
                backgroundColor: 'rgba(75, 192, 192, 0.2)', // Verde claro
                borderWidth: 2, // Ancho del borde
                fill: true, // No rellenar el área debajo de la línea
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const yearlyChart = new Chart(ctxYearly, {
        type: 'bar', // Tipo inicial del gráfico
        data: {
            labels: yearlyData.map(d => d.year),
            datasets: [{
                label: 'Producción Anual',
                data: yearlyData.map(d => d.total),
                borderColor: 'rgba(255, 99, 132, 1)', // Rosa
                backgroundColor: 'rgba(75, 192, 192, 0.2)', // Verde claro
                borderWidth: 2, // Ancho del borde
                fill: true, // No rellenar el área debajo de la línea
            }]
        },
        options: {
            responsive: true
        }
    });

    const categoryChart = new Chart(ctxCategory, {
        type: 'bar', // Tipo inicial del gráfico
        data: {
            labels: categoryData.map(d => d.category),
            datasets: [{
                label: 'Producción x Categoria mensual o segun rango de fecha',
                data: categoryData.map(d => d.total),
                backgroundColor: ['#f48fb1', '#d32f2f', '#1976d2', '#ff5722'],
                borderColor: ['#f48fb1', '#d32f2f', '#1976d2','#ff5722'],
                borderWidth: 2, // Ancho del borde
                fill: true, // No rellenar el área debajo de la línea
            }]
        },
        options: {
            responsive: true
        }
    });
};

//document.addEventListener('DOMContentLoaded', );
//fin reporte produccion  

//llamado de funciones

document.addEventListener('DOMContentLoaded', () => {

    const currentURL = window.location.pathname;

    if (currentURL === "/report/report_production") {
        creatReportProductionCharts();
    } else if(currentURL === "/report/report_sales"){
        createReportSalesCharts();
    } else if(currentURL === "/report/net-profit"){
        createAndUpdateChart();
    }else if(currentURL === "/report/report_expenses"){
        console.log(currentURL);
        creatReportExpensesCharts();
    }
});

/*fin graficos*/


