document.addEventListener('DOMContentLoaded', function () {
    // Gráfico de total de empleados, clientes y proveedores
    var totalChart = document.getElementById('usuarios');
    if (totalChart) {
        var empleados = parseInt(totalChart.getAttribute('data-empleados'));
        var clientes = parseInt(totalChart.getAttribute('data-clientes'));
        var proveedores = parseInt(totalChart.getAttribute('data-proveedores'));

        new Chart(totalChart, {
            type: 'pie',
            data: {
                datasets: [{
                    data: [empleados, clientes, proveedores],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.75)',
                        'rgba(54, 162, 235, 0.75)',
                        'rgba(255, 206, 86, 0.75)'
                    ],
                    borderWidth: 1,
                    borderColor: 'rgba(255, 255, 255, 0.9)'
                }],
                labels: ['Colaboradores', 'Clientes', 'Proveedores']
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        enabled: true
                    },
                    legend: {
                        display: true,
                        labels: {
                            color: '#333'
                        }
                    }
                }
            }
        });
    }

    // Gráfico de empleados, clientes y proveedores inactivos
    var inactiveChart = document.getElementById('usuariosinactivos');
    if (inactiveChart) {
        var empleadosInactivos = parseInt(inactiveChart.getAttribute('data-empleados-inactivos'));
        var clientesInactivos = parseInt(inactiveChart.getAttribute('data-clientes-inactivos'));
        var proveedoresInactivos = parseInt(inactiveChart.getAttribute('data-proveedores-inactivos'));

        new Chart(inactiveChart, {
            type: 'pie',
            data: {
                datasets: [{
                    data: [empleadosInactivos, clientesInactivos, proveedoresInactivos],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.75)',   // Rojo para empleados inactivos
                        'rgba(153, 102, 255, 0.75)', // Morado para clientes inactivos
                        'rgba(255, 159, 64, 0.75)'   // Naranja para proveedores inactivos
                    ],
                    borderWidth: 1,
                    borderColor: 'rgba(255, 255, 255, 0.9)'
                }],
                labels: ['Colaboradores Inactivos', 'Clientes Inactivos', 'Proveedores Inactivos']
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        enabled: true
                    },
                    legend: {
                        display: true,
                        labels: {
                            color: '#333'
                        }
                    }
                }
            }
        });
    }
     // Gráfico de incidencias por tipo
     var ctxIncidencias = document.getElementById('incidencias');
     if (ctxIncidencias) {
         var hardware = parseInt(ctxIncidencias.getAttribute('data-hardware'));
         var internet = parseInt(ctxIncidencias.getAttribute('data-internet'));
         var software = parseInt(ctxIncidencias.getAttribute('data-software'));
 
         new Chart(ctxIncidencias, {
             type: 'bar',
             data: {
                 labels: ['Hardware', 'Internet', 'Software'],
                 datasets: [{
                     label: 'Hardware',
                     data: [hardware, 0, 0],
                     backgroundColor: 'rgba(255, 99, 132, 0.75)',
                     borderColor: 'rgba(255, 99, 132, 1)',
                     borderWidth: 1
                 },
                 {
                     label: 'Internet',
                     data: [0, internet, 0],
                     backgroundColor: 'rgba(54, 162, 235, 0.75)',
                     borderColor: 'rgba(54, 162, 235, 1)',
                     borderWidth: 1
                 },
                 {
                     label: 'Software',
                     data: [0, 0, software],
                     backgroundColor: 'rgba(75, 192, 192, 0.75)',
                     borderColor: 'rgba(75, 192, 192, 1)',
                     borderWidth: 1
                 }]
             },
             options: {
                 responsive: true,
                 maintainAspectRatio: false,
                 scales: {
                     y: {
                         beginAtZero: true
                     }
                 },
                 plugins: {
                     legend: {
                         display: true
                     },
                     tooltip: {
                         enabled: true
                     }
                 }
             }
         });
     }
});
