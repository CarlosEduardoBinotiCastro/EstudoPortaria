<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Portaria') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.validate.ptbr.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>

        a {
            color: white;
        }

        .btn-primary-outline {
            background-color: transparent;
            border-color: transparent;
        }

        .my-error-class {
            color:#FF0000;  /* red */
        }

    </style>


</head>
<body>

    <div id="app">
        @guest
        @else


        <nav class="navbar navbar-light bg-primary ">



           <div class="container">
           <label style="text-align: center;  color: white; text-transform: uppercase;">Usuario Logado: <strong>{{Auth::user()->name}}</strong></label>
                    <ul class="navbar-nav ml-auto">
                            <!-- Authentication Links -->
                            <a href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                          document.getElementById('logout-form').submit();">
                             {{ __('SAIR') }}
                         </a>
                         <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                        </form>
                    </ul>
            </div>
        </nav>
        @endguest
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">

                <div class="container">
                    @auth
                    <a class="navbar-brand" href="{{ url('/carregarHome') }}">
                        {{ config('app.name', 'Portaria') }}
                    </a>

                    <div style="float: left" class="navbar-nav ml-auto col-md-6">

                    <div style="float: left" class="nav-item">
                        <a style="background-color: transparent; border-color: transparent; color: black;" class="btn btn-primary" href="{{url("/cadastrarVisitante")}}">Cadastar Visitante</a>
                    </div>

                    @if (Auth::user()->idGrupo == 1)
                        <div style="float: left" class="nav-item dropdown">
                            <button class="btn btn-primary-outline dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Cadastros
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{url("/cadastrarLocal")}}">Setor</a>
                            <a class="dropdown-item" href="{{url("/register/registrar")}}">Usuario</a>
                            </div>
                        </div>
                    @endif


                    <div style="float: left" class="nav-item dropdown">
                            <button class="btn btn-primary-outline dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              Listagens
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{url("/listarVisitante")}}">Visitantes</a>
                              <a class="dropdown-item" href="{{url("/listarLocais")}}">Setores</a>
                              <a class="dropdown-item" href="{{url("/listarVisitas")}}">Visitas</a>
                              @if (Auth::user()->idGrupo == 1) <a class="dropdown-item" href="{{url("/listarUsuarios")}}">Usuarios</a> @endif
                            </div>
                    </div>

                    <div style="float: left" class="nav-item dropdown">
                            <button class="btn btn-primary-outline dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              Relatorios
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{url("/relatorioVisitanteData")}}">Visitantes</a>
                              <a class="dropdown-item" href="{{url("/relatorioQuantitativoSetor")}}">Quantitativo por Setor</a>
                            </div>
                    </div>

                    @else

                    <div class="col-md-12">
                        <h3 style="text-align: center;"><strong>Portaria SEMFA</strong></h3>
                    </div>

                    @endauth
                </div>
                </div>
            </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

</body>


</html>

