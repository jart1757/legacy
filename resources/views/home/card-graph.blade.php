<div class="row">
    <div wire:ignore class="col-md-12">
            <!-- solid sales graph -->
            <div class="card bg-gradient-info">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="fas fa-chart-bar mr-1"></i>
                  Grafica Salidas por mes
                </h3>

                <div class="card-tools">

                </div>

              </div>
              <div class="card-body">
                <canvas class="chart" id="line-chart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <!-- /.card-body -->
              <div class="card-footer bg-transparent">

              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->



      <div wire:ignore class="col-md-12">
        <div class="card bg-gradient-info">
              <div class="card-header border-1">
                  <h3 class="card-title">
                      <i class="fas fa-chart-bar mr-1"></i>
                      Ventas por Departamento y Provincia
                  </h3>
              </div>
              <div class="card-body">
                  <canvas class="chart" id="bar-chart" style="min-height: 300px; height: 300px; max-width: 100%;"></canvas>
              </div>
          </div>
      </div>
      
  </div>
</div>

</div>

@section('styles')

@endsection

@section('js')
<script src="{{ asset('plugins/chart.js/Chart.min.js')}} "></script>


<script>

    // Sales graph chart
      var salesGraphChartCanvas = $('#line-chart').get(0).getContext('2d')
    
      var salesGraphChartData = {
        labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre','Noviembre','Diciembre'],
        datasets: [
          {
            label: '',
            fill: false,
            borderWidth: 2,
            lineTension: 0,
            spanGaps: true,
            borderColor: '#efefef',
            pointRadius: 3,
            pointHoverRadius: 7,
            pointColor: '#efefef',
            pointBackgroundColor: '#efefef',
            data: [
                    {{$listTotalVentasMes}}
    
              ]
          }
        ]
      }
      var salesGraphChartOptions = {
        maintainAspectRatio: false,
        responsive: true,
      tooltips: {
        callbacks: {
          label: (item) => `Ventas $${item.yLabel}`,
        },
      },
        legend: {
          display: false
        },
        scales: {
          xAxes: [{
            ticks: {
              fontColor: '#efefef'
            },
            gridLines: {
              display: false,
              color: '#efefef',
              drawBorder: false
            }
          }],
          yAxes: [{
            ticks: {
              stepSize: 5000,
              fontColor: '#efefef'
            },
            gridLines: {
              display: true,
              color: '#efefef',
              drawBorder: false
            }
          }]
        }
      }
      // This will get the first returned node in the jQuery collection.
      // eslint-disable-next-line no-unused-vars
      var salesGraphChart = new Chart(salesGraphChartCanvas, { // lgtm[js/unused-local-variable]
        type: 'line',
        data: salesGraphChartData,
        options: salesGraphChartOptions
      })
    
    </script>
    <script>
    var salesByRegion = @json($salesByRegion);

// Obtener departamentos únicos
var departamentos = [...new Set(salesByRegion.map(item => item.departamento))];


// Datos por departamento
var dataByDepartamento = departamentos.map(dep => {
    var totalStock = salesByRegion
        .filter(item => item.departamento === dep)
        .reduce((sum, item) => sum + Number(item.total_stock), 0); // Convertir a número

    return {
        label: dep,
        data: totalStock,
        backgroundColor: '#' + Math.floor(Math.random() * 16777215).toString(16)
    };
});


var ctx = document.getElementById('bar-chart').getContext('2d');

// Destruir el gráfico si ya existe
if (window.barChart) {
    window.barChart.destroy(); // Destruir el gráfico actual
}

window.barChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: departamentos,
        datasets: [{
            label: 'Stock por Departamento',
            data: dataByDepartamento.map(item => item.data),
            backgroundColor: dataByDepartamento.map(item => item.backgroundColor)
        }]
    },
    options: {
        responsive: true,
        legend: { display: false },
        scales: {
            x: { 
              
                title: { display: true, text: 'Departamentos' }
                
            },
            y: { 
                title: { display: true, text: 'Cantidad de Stock' },
                min: 0, // Asegura que el mínimo sea cero
                ticks: {
                    beginAtZero: true,
                    suggestedMin: 0,  // Mantén esto a 0
                    stepSize: 1,
                    callback: function(value) { 
                        return value < 1 ? 0 : value; // Si el valor es menor que 1, muestra 0
                    }
                }
            }
        },
        onClick: function (event, elements) {
            if (elements.length > 0) {
                var index = elements[0].index;
                var departamento = departamentos[index];

                // Filtrar provincias del departamento seleccionado
                var provincias = salesByRegion.filter(item => item.departamento === departamento);

                // Generar gráfico de provincias
                var provinciaLabels = provincias.map(p => p.provincia);
                var provinciaData = provincias.map(p => p.total_stock);

                var provinciaChartCanvas = document.getElementById('provincia-chart').getContext('2d');

                if (window.provinciaChart) {
                    window.provinciaChart.destroy();
                }

                window.provinciaChart = new Chart(provinciaChartCanvas, {
                    type: 'bar',
                    data: {
                        labels: provinciaLabels,
                        datasets: [{
                            label: 'Stock por Provincia en ' + departamento,
                            data: provinciaData,
                            backgroundColor: '#ff6384'
                        }]
                    },
                    options: {
                        responsive: true,
                        legend: { display: false },
                        scales: {
                            x: { title: { display: true, text: 'Provincias' } },
                            y: { 
                                title: { display: true, text: 'Cantidad de Stock' },
                                min: 0, // Asegura que el mínimo sea cero
                                ticks: {
                                    beginAtZero: true, 
                                    suggestedMin: 0,
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }
        }
    }
});

  </script>
  
  

@endsection