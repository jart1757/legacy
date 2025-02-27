<nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="/" class="nav-link {{$title=='Inicio'?'active':''}}">
          <i class="fas fa-store"></i> Inicio
        </a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{route('sales.create')}}" class="nav-link {{$title=='Ventas'?'active':''}}">
          <i class="fas fa-cart-plus"></i> Crear venta
        </a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        @livewire('search')
      </li>

      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
          <!--<img src="{{auth()->user()->imagen}}" class="user-image img-circle elevation-2" alt="User Image">-->
          <span class="d-none d-md-inline">
            {{auth()->user()->name}}
          </span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
          <!-- User image -->
          <li class="user-header bg-lightblue">
            <!--<img src="{{auth()->user()->imagen}}" class="img-circle elevation-2" alt="User Image">-->
  
            <p>
              {{auth()->user()->name}}
              <small>
                {{auth()->user()->admin ? 'Administrador' : 'Vendedor'}}
              </small>
            </p>
          </li>
          <!-- Menu Body -->
  
          <!-- Menu Footer-->
          <li class="user-footer">
            <a href="{{route('users.show',auth()->user())}}" class="btn btn-default btn-flat">Perfil</a>
            <a class="btn btn-default btn-flat float-right" href="{{ route('logout') }}"
            onclick="event.preventDefault();
                          document.getElementById('logout-form').submit();">
             Salir
         </a>
  
         <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
             @csrf
         </form>
          </li>
        </ul>
      </li>

      <li class="nav-item">

        <label class="switch mt-2">
          <input type="checkbox" id="check-dark">
          <span id="dark-icon" class="text-white"></span>
        </label>

      </li>

      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>

    </ul>
  </nav>