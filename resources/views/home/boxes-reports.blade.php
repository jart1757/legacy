<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box">
        <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-file-invoice-dollar"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Ventas</span>
          <span class="info-box-number">
            {{$cantidadVentas}}
            
          </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box mb-3">
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

    <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box mb-3">
        <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-shopping-basket"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">
            Articulos Vendidos
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
    <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box mb-3">
        <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-tshirt"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Productos Vendidos</span>
          <span class="info-box-number">
            {{$cantidadProductos}}
          </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
</div>

<!-- SEGUNDA FILA -->

<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box">
        <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-tshirt"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Productos</span>
          <span class="info-box-number">
            {{$cantidadProducts}}
            
          </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box mb-3">
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

    <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box mb-3">
        <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-th"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Categorias</span>
          <span class="info-box-number">
            {{$cantidadCategories}}
          </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box mb-3">
        <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-users"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Clientes</span>
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