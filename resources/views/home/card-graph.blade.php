<div class="row">
  <div wire:ignore class="col-md-12">
      <!-- Gráfica de línea (Ventas por mes) -->
      <div class="card bg-gradient-info">
          <div class="card-header border-0">
              <h3 class="card-title">
                  <i class="fas fa-chart-bar mr-1"></i>
                  Gráfica Salidas por mes
              </h3>
          </div>
          <div class="card-body">
              <canvas class="chart" id="line-chart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;">
              </canvas>
          </div>
      </div>
  </div>

  <div wire:ignore class="col-md-12">
      <!-- Gráfica de barras (Departamentos) -->
      <div class="card bg-gradient-info">
          <div class="card-header border-1">
              <h3 class="card-title">
                  <i class="fas fa-chart-bar mr-1"></i>
                  Ventas por Departamento y Provincia
              </h3>
          </div>
          <div class="card-body">
              <canvas class="chart" id="bar-chart" style="min-height: 300px; height: 300px; max-width: 100%;">
              </canvas>
          </div>
      </div>
  </div>
</div>

<!-- Modal para Provincias -->
<div class="modal fade" id="modalProvincias" tabindex="-1" aria-labelledby="modalProvinciasLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="modalProvinciasLabel">Provincias</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <!-- Canvas para la gráfica de Provincias -->
      <canvas id="provincia-chart" style="min-height: 300px; max-width: 100%;">
      </canvas>
    </div>
  </div>
</div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Clientes por Categoría y Mes</h3>
    </div>
    <div class="card-body">
        <canvas id="clients-chart" width="400" height="200"></canvas>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    var clientsByCategory = @json($clientsByCategory);
    
    var categories = [...new Set(clientsByCategory.map(item => item.category_id))];
    var labels = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    
    var datasets = categories.map(category => {
        var data = Array(12).fill(0);
        clientsByCategory.filter(c => c.category_id === category).forEach(c => {
            data[c.month - 1] = c.total;
        });

        return {
            label: 'Categoría ' + category,
            backgroundColor: '#' + Math.floor(Math.random() * 16777215).toString(16),
            borderColor: '#' + Math.floor(Math.random() * 16777215).toString(16),
            borderWidth: 1,
            data: data
        };
    });

    var ctx = document.getElementById('clients-chart').getContext('2d');
    new Chart(ctx, {
        type: 'bar', // Cambiado a 'bar' para gráfico de barras
        data: { labels: labels, datasets: datasets },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Cantidad de Clientes'
                    }
                }
            }
        }
    });
});
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
    var clientsByCategory = @json($clientsByCategory);

    // Mapeo de categorías con nombres reales
    var categoryNames = {
        1: 'Bonificado',
        2: 'Mayorista',
        3: 'Preferente',
        4: 'Reconsumo'
    };

    var categories = [...new Set(clientsByCategory.map(item => item.category_id))];
    var labels = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

    var datasets = categories.map(category => {
        var data = Array(12).fill(0);
        clientsByCategory.filter(c => c.category_id === category).forEach(c => {
            data[c.month - 1] = c.total;
        });

        return {
            label: categoryNames[category] || `Categoría ${category}`, // Usar nombre real o dejar el número
            backgroundColor: '#' + Math.floor(Math.random() * 16777215).toString(16),
            borderColor: '#' + Math.floor(Math.random() * 16777215).toString(16),
            borderWidth: 1,
            data: data
        };
    });

    var ctx = document.getElementById('clients-chart').getContext('2d');
    new Chart(ctx, {
        type: 'bar', // Gráfico de barras
        data: { labels: labels, datasets: datasets },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Cantidad de Clientes'
                    }
                }
            }
        }
    });
});

    </script>


@section('styles')
<!-- Tus estilos adicionales si los necesitas -->
@endsection

@section('js')
<!-- Asegúrate de tener la librería de Chart.js y jQuery/Bootstrap -->
<script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
<!-- Ejemplo: -->
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script> -->

<script>
/* -----------------------------------------------------
 GRÁFICA DE LÍNEAS (VENTAS POR MES)
----------------------------------------------------- */
var salesGraphChartCanvas = document.getElementById('line-chart').getContext('2d');
var salesGraphChartData = {
  labels: [
      'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 
      'Junio', 'Julio', 'Agosto', 'Septiembre', 
      'Octubre', 'Noviembre', 'Diciembre'
  ],
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
              {{ $listTotalVentasMes }}
          ]
      }
  ]
};

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
};

var salesGraphChart = new Chart(salesGraphChartCanvas, {
  type: 'line',
  data: salesGraphChartData,
  options: salesGraphChartOptions
});
</script>

<script>
/* -----------------------------------------------------
 GRÁFICA DE BARRAS (DEPARTAMENTOS) Y EVENTO PARA MODAL DE PROVINCIAS
----------------------------------------------------- */
// Datos recibidos desde el controlador
var salesByRegion = @json($salesByRegion);

// Variables globales para almacenar datos del departamento seleccionado
var selectedDepartamento = '';
var selectedProvinciaLabels = [];
var selectedProvinciaData = [];

// Extraer la lista de departamentos únicos
var departamentos = [...new Set(salesByRegion.map(item => item.departamento))];

// Calcular la sumatoria total de 'total_stock' por departamento
var dataByDepartamento = departamentos.map(dep => {
  var totalStock = salesByRegion
      .filter(item => item.departamento === dep)
      .reduce((sum, item) => sum + Number(item.total_stock), 0);
  return {
      label: dep,
      data: totalStock,
      backgroundColor: '#' + Math.floor(Math.random() * 16777215).toString(16)
  };
});

// Crear la gráfica de Departamentos
var ctx = document.getElementById('bar-chart').getContext('2d');
if (window.barChart) {
  window.barChart.destroy();
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
              min: 0,
              ticks: {
                  beginAtZero: true,
                  suggestedMin: 0,
                  stepSize: 1,
                  callback: function(value) {
                      return value < 1 ? 0 : value;
                  }
              }
          }
      },
      // Evento onClick: almacena los datos y muestra el modal
      onClick: function (event, elements) {
          if (elements.length > 0) {
              var index = elements[0].index;
              selectedDepartamento = departamentos[index];
              var provincias = salesByRegion.filter(item => item.departamento === selectedDepartamento);
              selectedProvinciaLabels = provincias.map(p => p.provincia);
              selectedProvinciaData = provincias.map(p => p.total_stock);
              // Abre el modal
              $('#modalProvincias').modal('show');
          }
      }
  }
});

// Cuando el modal se muestra, se crea la gráfica de Provincias
$('#modalProvincias').on('shown.bs.modal', function(){
  var provinciaChartCanvas = document.getElementById('provincia-chart').getContext('2d');
  if (window.provinciaChart) {
      window.provinciaChart.destroy();
  }
  window.provinciaChart = new Chart(provinciaChartCanvas, {
      type: 'bar',
      data: {
          labels: selectedProvinciaLabels,
          datasets: [{
              label: 'Stock por Provincia en ' + selectedDepartamento,
              data: selectedProvinciaData,
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
                  min: 0,
                  ticks: {
                      beginAtZero: true,
                      suggestedMin: 0,
                      stepSize: 1
                  }
              }
          }
      }
  });
});
</script>
@endsection
