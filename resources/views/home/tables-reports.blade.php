<div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            <b>Productos con mas salidas hoy</b>
          </h3>
          <div class="card-tools">
  
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
          <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th style="width: 10px">#</th>
                <th>Nombre</th>
                <th>Precio Total</th>
                <th>Cantidad</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($productosMasVendidosHoy as $product)

              <tr>
                <td>{{$product->product_id}}</td>
                <td>{{$product->name}}</td>
                <td>{{money($product->price)}}</td>
                <td>
                  <span class="badge bg-success">
                    {{$product->total_quantity}}
                  </span>
                </td>
                <td>
                  {{money($product->price*$product->total_quantity)}}
                </td>
              </tr>
                  
              @empty
              <tr>
                <td colspan="10">
                  Sin registros
                </td>
              </tr>
                
              @endforelse

            </tbody>
          </table>
        </div>
        </div>
        <!-- /.card-body -->
      </div> 
  
    </div>
    <!-- /.col-md-6 -->
      <!--.col-md-6 -->
    <div class="col-md-6">
  
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            <b>Productos mas vendidos este mes</b>
          </h3>
  
          <div class="card-tools">
  
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
          <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th style="width: 10px">#</th>
                <th>Nombre</th>
                <th>Precio Total</th>
                <th>Cantidad</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
             
              @forelse ($productosMasVendidosMes as $product)

              <tr>
                <td>{{$product->product_id}}</td>
                <td>{{$product->name}}</td>
                <td>{{money($product->price)}}</td>
                <td>
                  <span class="badge bg-success">
                    {{$product->total_quantity}}
                  </span>
                </td>
                <td>
                  {{money($product->price*$product->total_quantity)}}
                </td>
              </tr>
                  
              @empty
              <tr>
                <td colspan="10">
                  Sin registros
                </td>
              </tr>
                
              @endforelse
            </tbody>
          </table>
        </div>
        </div>
        <!-- /.card-body -->
      </div> 
    </div>
    <!-- /.col-md-6 -->
  </div>
  <!-- /.row -->
  
  {{-- SEGUNDA FILA --}}
  
  <div class="row">
    <div class="col-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            <b>
            Productos mas vendidos
          </b>
          </h3>
  
          <div class="card-tools">
  
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
          <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th style="width: 10px">#</th>
                <th>Nombre</th>
                <th>Precio Total</th>
                <th>Cantidad</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>           
              @forelse ($productosMasVendidos as $product)

              <tr>
                <td>{{$product->product_id}}</td>
                <td>{{$product->name}}</td>
                <td>{{money($product->price)}}</td>
                <td>
                  <span class="badge bg-success">
                    {{$product->total_quantity}}
                  </span>
                </td>
                <td>
                  {{money($product->price*$product->total_quantity)}}
                </td>
              </tr>
                  
              @empty
              <tr>
                <td colspan="10">
                  Sin registros
                </td>
              </tr>
                
              @endforelse
            </tbody>
          </table>
        </div>
        </div>
        <!-- /.card-body -->
      </div>
    </div>
    <div class="col-6">
   
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            <b>
            Productos agregados recientemente
          </b>
          </h3>
  
          <div class="card-tools">
  
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
          <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th style="width: 10px">#</th>
                <th>Nombre</th>
                <th>Stock</th>
              </tr>
            </thead>
            <tbody>
  
              @forelse ($productosRecientes as $product)

              <tr>
                <td>{{$product->id}}</td>
                <td>{{$product->name}}</td>
                <td>
                  
                    {!!$product->stockLabel!!}
             
                </td>

              </tr>
                  
              @empty
              <tr>
                <td colspan="10">
                  Sin registros
                </td>
              </tr>
                
              @endforelse
  
            </tbody>
          </table>
        </div>
        </div>
        <!-- /.card-body -->
      </div> 
    </div>
  </div>