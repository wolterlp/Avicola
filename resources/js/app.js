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

            // Solo agrega el evento si el elemento existe
        if (menuTrigger) {
            menuTrigger.addEventListener('click', function(event) {
                event.stopPropagation();
                const dropdown = this.querySelector('.group-hover\\:block');
                dropdown.classList.toggle('hidden');
            });
        }

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


    function calculoPanal() {
        const quantityInput = document.getElementById('quantity');
        const panalInput = document.getElementById('panal');
        
        if (quantityInput && panalInput) {
            const quantity = parseFloat(quantityInput.value);
            const huevosPorPanal = 30; // Define el número de huevos por panal
    
            if (!isNaN(quantity)) {
                // Calcular número de panales y huevos
                const totalPanales = Math.floor(quantity / huevosPorPanal);
                const huevosRestantes = (quantity % huevosPorPanal).toFixed(); // Fracción de huevos restantes
    
                // Mostrar resultado
                panalInput.value = `${totalPanales} panales y ${huevosRestantes} huevos`;
            } else {
                panalInput.value = ''; // Limpiar si la cantidad no es un número válido
            }
        }
    }

//reporte sales 

function createReportSalesCharts() {
    var ctxDaily = document.getElementById('dailySalesChart').getContext('2d');
    var ctxMonthly = document.getElementById('monthlySalesChart').getContext('2d');
    var ctxYearly = document.getElementById('yearlySalesChart').getContext('2d');

    // Obtener los datos de ventas desde los scripts JSON
    const dailySalesData = JSON.parse(document.getElementById('dailySalesData').textContent);
    const monthlySalesData = JSON.parse(document.getElementById('monthlySalesData').textContent);
    const yearlySalesData = JSON.parse(document.getElementById('yearlySalesData').textContent);

    // Transformar los datos para los gráficos
    const dailyLabels = dailySalesData.map(data => data.date);
    const dailyValues = dailySalesData.map(data => parseFloat(data.total));
    const dailyTotalEggs = dailySalesData.map(data => parseFloat(data.total_eggs)); // Total Huevos

    const monthlyLabels = monthlySalesData.map(data => `${data.year}-${data.month}`);
    const monthlyValues = monthlySalesData.map(data => parseFloat(data.total)); // Asumiendo el formato correcto
    const monthlyTotalEggs = monthlySalesData.map(data => parseFloat(data.total_eggs)); // Total Huevos

    const yearlyLabels = yearlySalesData.map(data => data.year); // Asumiendo el formato correcto
    const yearlyValues = yearlySalesData.map(data => parseFloat(data.total)); // Asumiendo el formato correcto
    const yearlyTotalEggs = yearlySalesData.map(data => parseFloat(data.total_eggs)); // Total Huevos


    // Configuración de los gráficos
    var dailyChartData = {
        labels: dailyLabels,
        datasets: [
            {
                label: 'Total Huevos',
                data: dailyTotalEggs,
                backgroundColor: 'rgba(255, 206, 86, 0.2)',
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 1,
                fill: false,
                type: 'line'  // Esta línea será un gráfico de línea
            },
            {
                label: 'Ventas Diarias',
                data: dailyValues,
                backgroundColor: '#4caf50',
                borderColor: '#388e3c',
                borderWidth: 1,
                fill: false,
                type: 'line'  // Esta línea también será un gráfico de línea
            }
        ]
    };

    var monthlyChartData = {
        labels: monthlyLabels,
        datasets: [
            {
                label: 'Total Huevos',
                data: monthlyTotalEggs,
                backgroundColor: 'rgba(255, 206, 86, 0.2)',
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 1,
                fill: false,
                type: 'line'  // Esta línea será un gráfico de línea
            },
            {
                label: 'Ventas Mensuales',
                data: monthlyValues,
                backgroundColor: ['#4caf50', '#f44336', '#2196f3'],
                borderColor: ['#388e3c', '#d32f2f', '#1976d2'],
                borderWidth: 1
            }
        ]
    };

    var yearlyChartData = {
        labels: yearlyLabels,
        datasets: [
            {
                label: 'Total Huevos',
                data: yearlyTotalEggs,
                backgroundColor: 'rgba(255, 206, 86, 0.2)',
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 1,
                fill: false,
                type: 'line'  // Esta línea será un gráfico de línea
            },
            {
                label: 'Ventas Anuales',
                data: yearlyValues,
                backgroundColor: '#2196f3',
                borderColor: '#1976d2',
                borderWidth: 1
            }
        ]
    };

    var chartOptions = {
        responsive: true,  // Asegura que el gráfico sea responsivo
        maintainAspectRatio: false,  // Permite que el gráfico no mantenga una relación de aspecto fija
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
                borderColor: '#1976d2', // Rosa
                backgroundColor: '#1976d2', 
                borderWidth: 2, // Ancho del borde
                fill: false, // No rellenar el área debajo de la línea
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,  // Permite que el gráfico no mantenga una relación de aspecto fija
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
                //borderColor: 'rgba(255, 99, 132, 1)', // Rosa
                backgroundColor: '#00796B', 
                borderWidth: 2, // Ancho del borde
                fill: true, // No rellenar el área debajo de la línea
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,  // Permite que el gráfico no mantenga una relación de aspecto fija
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
                //borderColor: 'rgba(255, 99, 132, 1)', // Rosa
                backgroundColor: '#1E3A8A', 
                borderWidth: 2, // Ancho del borde
                fill: false, // No rellenar el área debajo de la línea
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,  // Permite que el gráfico no mantenga una relación de aspecto fija
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

    const categoryChart = new Chart(ctxCategory, {
        type: 'bar', // Tipo inicial del gráfico
        data: {
            labels: categoryData.map(d => d.category),
            datasets: [{
                label: 'Producción x Categoria Mensual-Fecha',
                data: categoryData.map(d => d.total),
                backgroundColor: ['#673AB7', '#d32f2f', '#1976d2', '#ff5722','#4caf50'],
                borderColor: ['#673AB7', '#d32f2f', '#1976d2','#ff5722','#4caf50'],
                borderWidth: 2, // Ancho del borde
                fill: true, // No rellenar el área debajo de la línea
            }]
        },
        options: {
            maintainAspectRatio: false,  // Permite que el gráfico no mantenga una relación de aspecto fija
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
};

//fin reporte produccion  

//Reporte Gastos

function createReportExpensesCharts() {

    //document.addEventListener('DOMContentLoaded', () => {
        // Obtén los datos desde los scripts JSON
        const dailyData = JSON.parse(document.getElementById('dailyExpensesData').textContent);
        const monthlyData = JSON.parse(document.getElementById('monthlyExpensesData').textContent);
        const yearlyData = JSON.parse(document.getElementById('yearlyExpensesData').textContent);
        
    
        // Configura los contextos para los gráficos
        const ctxDailyExpenses = document.getElementById('dailyExpensesChart').getContext('2d');
        const ctxMonthlyExpenses = document.getElementById('monthlyExpensesChart').getContext('2d');
        const ctxYearlyExpenses = document.getElementById('yearlyExpensesChart').getContext('2d');
        
    
        // Inicializa los gráficos
        const dailyChart = new Chart(ctxDailyExpenses, {
            type: 'line', // Tipo inicial del gráfico
            data: {
                labels: dailyData.map(d => d.date),
                datasets: [{
                    label: 'Gastos Diarios',
                    data: dailyData.map(d => d.total),
                    borderColor: '#0097A7', // Rosa
                    backgroundColor: '#4caf50', 
                    borderWidth: 2, // Ancho del borde
                    fill: true, // No rellenar el área debajo de la línea
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,  // Permite que el gráfico no mantenga una relación de aspecto fija
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
    
        const monthlyChart = new Chart(ctxMonthlyExpenses, {
            type: 'line', // Tipo inicial del gráfico
            data: {
                labels: monthlyData.map(d => `${d.month}/${d.year}`),
                datasets: [{
                    label: 'Gastos Mensuales',
                    data: monthlyData.map(d => d.total),
                    //borderColor: 'rgba(255, 99, 132, 1)', // Rosa
                    backgroundColor: '#0097A7', 
                    borderWidth: 2, // Ancho del borde
                    fill: true, // No rellenar el área debajo de la línea
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,  // Permite que el gráfico no mantenga una relación de aspecto fija
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
    
        const yearlyChart = new Chart(ctxYearlyExpenses, {
            type: 'bar', // Tipo inicial del gráfico
            data: {
                labels: yearlyData.map(d => d.year),
                datasets: [{
                    label: 'Gastos Anuales',
                    data: yearlyData.map(d => d.total),
                    //borderColor: 'rgba(255, 99, 132, 1)', // Rosa
                    backgroundColor: '#2196f3', 
                    borderWidth: 2, // Ancho del borde
                    fill: true, // No rellenar el área debajo de la línea
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,  // Permite que el gráfico no mantenga una relación de aspecto fija
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
    };

// Fin de Reporte Gatos  createReportExpensesCharts

//llamado de funciones

document.addEventListener('DOMContentLoaded', () => {

    const currentURL = window.location.pathname;
    console.log(currentURL);
    if (currentURL === "/report/report_production") {
        creatReportProductionCharts();
    } else if(currentURL === "/report/report_sales"){
        createReportSalesCharts();
    } else if(currentURL === "/report/net-profit"){
        createAndUpdateChart();
    }else if(currentURL === "/report/report_expenses"){
        createReportExpensesCharts();
    } 

 
});

document.addEventListener('DOMContentLoaded', function() {
    const currentURL = window.location.pathname;
    const patron = /^\/sales\/\d+\/edit$/;
    
    const quantityInput = document.getElementById('quantity');
    
    // Detectar cambios en el campo de cantidad
    if(currentURL === "/sales/create" || currentURL === "/eggProduction/create" || patron.test(currentURL) ){
        quantityInput.addEventListener('input', calculoPanal);
    }  
    
});

/*fin graficos*/


