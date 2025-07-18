<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Sistema inventario - @yield('title')</title>

        <link href="{{ asset('css/template.css') }}" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        @stack('css')

    </head>
    @auth
        <body class="sb-nav-fixed">

            <x-navigation-header></x-navigation-header>

            <div id="layoutSidenav">
                <x-navigation-menu></x-navigation-menu>
                <div id="layoutSidenav_content">
                    <main>
                        @yield('content')
                    </main>
                    <x-footer></x-footer>
                </div>
            </div>


            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
            <script src="{{ asset('js/scripts.js') }}"></script>

            @stack('js')

        </body>
    @endauth

    @guest
        @include('pages.401')
    @endguest
</html>
