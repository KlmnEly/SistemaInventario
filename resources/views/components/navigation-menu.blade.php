<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link" href="{{ route('panel') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Panel
                </a>
                <div class="sb-sidenav-menu-heading">Principal</div>

                @can('ver-producto')
                    <a class="nav-link " href="{{ route('productos.index') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-computer"></i></div>
                        Productos
                    </a>
                @endcan

                @can('ver-historial')
                <a class="nav-link" href="{{ route('historiales.index') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-book"></i></div>
                    Historial
                </a>
                @endcan


                <div class="sb-sidenav-menu-heading">Componentes</div>
                @can('ver-categoria')
                <a class="nav-link " href="{{ route('categorias.index') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-list"></i></div>
                    Categorias
                </a>
                @endcan

                @can('ver-marca')
                <a class="nav-link " href="{{ route('marcas.index') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-bookmark"></i></div>
                    Marcas
                </a>
                @endcan

                @can('ver-ubicacion')
                <a class="nav-link " href="{{ route('ubicaciones.index') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-location-dot"></i></div>
                    Ubicaciones
                </a>
                @endcan

                @can('ver-condicion')
                <a class="nav-link " href="{{ route('condiciones.index') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-heart-pulse"></i></div>
                    Condiciones
                </a>
                @endcan

                @can('ver-tipoEvento')
                <a class="nav-link" href="{{ route('tiposEvento.index') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-calendar-days"></i></div>
                    Tipos de Evento
                </a>
                @endcan


                <div class="sb-sidenav-menu-heading">Otros</div>
                @can('ver-user')
                <a class="nav-link" href="{{ route('users.index') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-user"></i></div>
                    Usuarios
                </a>
                @endcan

                @can('ver-role')
                <a class="nav-link" href="{{ route('roles.index') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-person-circle-plus"></i></div>
                    Roles
                </a>
                @endcan

            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Bienvenido:</div>
            {{ auth()->user()->name }}
        </div>
    </nav>
</div>
