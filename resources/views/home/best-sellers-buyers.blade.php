<div class="row">
    
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><b>Mejores Usuarios</b></h3>
          <div class="card-tools">
  
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
          <ul class="users-list clearfix">
           @foreach ($bestBuyers as $delivery) 
            <li>
              <i class="fas fa-user-tie" style="font-size: 3rem"></i>
              <a href="{{route('deliveries.show',$delivery)}}" class="users-list-name mt-2">
                {{$delivery->name}}
              </a>
              <span>{{money($delivery->total)}}</span>
            </li>

           @endforeach 
  
          </ul>
          <!-- /.users-list -->
        </div>
        <!-- /.card-body -->
        <div class="card-footer text-center">
          <a href="{{route('deliveries')}}">Ir a usuarios</a>
        </div>
        <!-- /.card-footer -->
      </div>
    </div>
  </div>