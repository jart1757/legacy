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

@endsection