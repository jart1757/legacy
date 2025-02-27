<div class="row">
    <div class="col-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><b>Mejores vendedores</b></h3>
          <div class="card-tools">
  
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
          <ul class="users-list clearfix">
             @foreach ($bestSellers as $user) 
            <li>
              {{--<x-image :item="$user" />--}}
              <a href="{{route('users.show',$user)}}" class="users-list-name mt-2">
                {{$user->name}}
              </a>
              <span>{{money($user->total)}}</span>
            </li>

             @endforeach 
  
          </ul>
          <!-- /.users-list -->
        </div>
        <!-- /.card-body -->
        <div class="card-footer text-center">
          <a href="{{route('users')}}">Ir a usuarios</a>
        </div>
        <!-- /.card-footer -->
      </div>
    </div>
    <div class="col-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><b>Mejores compradores</b></h3>
          <div class="card-tools">
  
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
          <ul class="users-list clearfix">
           @foreach ($bestBuyers as $client) 
            <li>
              <i class="fas fa-user-tie" style="font-size: 3rem"></i>
              <a href="{{route('clients.show',$client)}}" class="users-list-name mt-2">
                {{$client->name}}
              </a>
              <span>{{money($client->total)}}</span>
            </li>

           @endforeach 
  
          </ul>
          <!-- /.users-list -->
        </div>
        <!-- /.card-body -->
        <div class="card-footer text-center">
          <a href="{{route('clients')}}">Ir a clientes</a>
        </div>
        <!-- /.card-footer -->
      </div>
    </div>
  </div>