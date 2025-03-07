<div class="row">
    <div class="col-12 col-sm-12 col-md-4">
      <div class="info-box">
        <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-file-invoice-dollar"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Cantidad de Salidas</span>
          <span class="info-box-number">
            {{$cantidadVentas}}
            
          </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-12 col-sm-12 col-md-4">
      <div class="info-box mb-4">
        <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-money-check-alt"></i></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Total ventas</span>
          <span class="info-box-number">
            {{money($totalventas)}}
        </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- fix for small devices only -->
    <div class="clearfix hidden-md-up"></div>

    <div class="col-12 col-sm-12 col-md-4">
      <div class="info-box mb-4">
        <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-shopping-basket"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">
            Cajas Vendidas
          </span>
          <span class="info-box-number">
            {{$cantidadArticulos}}
          </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <!-- /.col -->
</div>

<!-- SEGUNDA FILA -->

<div class="row">
    <!-- /.col -->
    <div class="col-12 col-sm-12 col-md-4">
      <div class="info-box mb-4">
        <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-shopping-basket"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Stock total</span>
          <span class="info-box-number">
            {{$cantidadStock}}
          </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- fix for small devices only -->
    <div class="clearfix hidden-md-up"></div>

    <div class="col-12 col-sm-12 col-md-4">
      <div class="info-box mb-4">
        <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-th"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Tipos de Clientes</span>
          <span class="info-box-number">
            {{$cantidadCategories}}
          </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-12 col-sm-12 col-md-4">
      <div class="info-box mb-4">
        <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-users"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Clientes Registrados</span>
          <span class="info-box-number">
            {{$cantidadClients}}
          </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
</div>