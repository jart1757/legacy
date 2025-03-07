<div class="row">
    <div class="col-lg-4 col-12">
      <!-- small box -->
      <div class="small-box bg-info">
        <div class="inner">
          <h3>{{$ventasHoy}}</h3>

          <p>CANTIDAD DE SALIDAS HOY</p>
        </div>
        <div class="icon">
            <i class="fas fa-file-invoice-dollar"></i>
        </div>
        <a href="{{route('sales.list')}}" class="small-box-footer">Ir a Salidas <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-4 col-12">
      <!-- small box -->
      <div class="small-box bg-purple">
        <div class="inner">
          <h3>{{money($totalventasHoy)}}</h3>

          <p>TOTAL DE INGRESO HOY</p>
        </div>
        <div class="icon">
            <i class="fas fa-money-check-alt"></i>
        </div>
        <a href="{{route('sales.list')}}" class="small-box-footer">Ir a Salidas <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-4 col-12">
      <!-- small box -->
      <div class="small-box bg-primary">
        <div class="inner">
          <h3>{{$articulosHoy}}</h3>

          <p>CAJAS TOTALES VENDIDAS HOY</p>
        </div>
        <div class="icon">
            <i class="fas fa-shopping-basket"></i>
        </div>
        <a href="{{route('sales.list')}}" class="small-box-footer">Ir a Salidas <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <!-- ./col -->
</div>