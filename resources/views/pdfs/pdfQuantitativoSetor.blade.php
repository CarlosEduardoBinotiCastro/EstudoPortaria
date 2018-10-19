
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
<body style="background-color: white;">

        @php
            $dataInicio = new DateTime($dataInicio);
            $dataFinal = new DateTime($dataFinal);
        @endphp
        <h3 class="col-md-12 offset-md-2"> Relatorio Quantitativo Por Setor</h3>
        <br>
        <h4 class="col-md-10 offset-md-2"> Referente as Datas de {{$dataInicio->format('d/m/Y')}} à {{$dataFinal->format('d/m/Y')}}</h4>
        <br>
        <div class="container">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                    <div class="table-responsive">
                        <div class="card">
                                <div class="card-header"><strong>RESULTADO</strong></div>
                                <table id="mytable" class="table table-bordred table-striped">

                                    <thead>
                                        <th>Setor</th>
                                        <th>Quantidade de Visitas</th>

                                    </thead>

                                    <tbody>

                                        @foreach ($quantitativoSetor as $setor)

                                        <tr>
                                            <td>{{$setor->nomeLocal}}</td>
                                            <td>{{$setor->quantidade}}</td>
                                        </tr>

                                        @endforeach

                                    </tbody>
                                </table>

                                <div class="offset-md-6">
                                <p><strong> Quantidade de Visitas:  {{$quantitativoVisitas}}</strong></p>
                                <p><strong> Quantidade de Visitantes:  {{$quantitativoVisitantes}}</strong></p>
                                </div>
                        </div>
                        <br>
                        </div>

                    </div>
                </div>
            </div>

</body>
</html>
