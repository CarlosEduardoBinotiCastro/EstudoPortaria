@extends('layouts.app')

@section('content')

@guest

@else



<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Relatorio Visitantes Em Um Período</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status')}}
                        </div>
                    @endif

                    <div>
                        <form action="{{url("/relatorioVisitanteData")}}" method="POST">
                            @csrf
                            <div class="form-group row offset-md-2">
                                <label class="col-md-3 text-md-right">Data Inicial</label>

                            @if (date('d') > 1)
                                <input class="form-control col-md-6" type="date" name="dado" value="{{  date('Y-m-d', strtotime("-".date('d', strtotime("-1 day"))." day"))}}" required>
                            @else
                                <input class="form-control col-md-6" type="date" name="dado" value="{{ date('Y-m-d') }}" required>
                            @endif

                            </div>

                            <div class="form-group row offset-md-2">
                                <label class="col-md-3 text-md-right">Data Final</label>
                                <input class="form-control col-md-6" type="date" name="dado2" value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="form-group row offset-md-4">
                                    <input class=" btn btn-primary col-md-5" type="submit" name="enviar">
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<br/><br/>
<div class="container">
        <div class="row">


            <div class="col-md-12">
            <h4>Visitantes Cadastrados</h4>
            <div class="table-responsive">


                  <table id="mytable" class="table table-bordred table-striped">

                       <thead>

                       <th>Foto</th> <!-- Foto 50 px por 50 px -->
                       <th>Nome</th>
                        <th>Tipo Do Documento</th>
                         <th>Número Do Documento</th>
                         <th>Data e Hora</th>
                         <th>Data e Hora Saida</th>
                         <th>Tempo de Visita</th>
                         <th>Local</th>
                         <th>Visitado</th>
                         <th>Assunto</th>
                       </thead>
        <tbody>

        @if(!isset($visitantes))

        @else
            @foreach ($visitantes as $visitante)


            @php
                    $dataEntrada = new DateTime($visitante->dataHora);
                    $dataSaida = new DateTime($visitante->dataHoraSaida);

                    $date = $dataSaida->diff($dataEntrada, true);
                    if ($date->format('%d') > 0){
                        $tempo = $date->format('Data de saida diferente Data da entrada');
                    }else{
                        if($date->format('%H') > 1){
                            $tempo = $date->format('%H horas e %i minutos');
                        }else{
                            $tempo = $date->format('%i minutos');
                        }
                    }

                    if($visitante->tipoDoc == 'CPF'){
                        $mask = "###.###.###-##";
                        $visitante->numeroDoc = str_replace(" ","",$visitante->numeroDoc);

                        for($i=0;$i<strlen($visitante->numeroDoc);$i++){
                            $mask[strpos($mask,"#")] = $visitante->numeroDoc[$i];
                        }
                        $visitante->numeroDoc = $mask;
                    }

                @endphp


            <tr>
                    <?php // echo "<td><img src='/foto/".$visitantes->urlFoto."' height='50px' width='50px'></td>" ?>
            <td><img id="myImg" src="/foto/{{$visitante->urlFoto}}" height="50px" width="50px" data-toggle="modal" data-target="#myModal{{$visitante->visitanteID}}"></td>
            <td> <a style="color: blue;" href="/informacoesVisitante/{{$visitante->visitanteID}}">{{$visitante->nomeVisitante}}</a></td>
                <td>{{$visitante->tipoDoc}}</td>
                <td>{{$visitante->numeroDoc}}</td>
                <td>{{$dataEntrada->format('d/m/Y H:i:s')}}</td>
                <td>{{$dataSaida->format('d/m/Y H:i:s')}}</td>
                <td>{{$tempo}}</td>
                <td>{{$visitante->nomeLocal}}</td>
                <td>{{$visitante->visitado}}</td>
                <td>{{$visitante->assunto}}</td>
                </tr>

                <div class='modal fade' id="myModal{{$visitante->visitanteID}}" role='dialog'>
                    <div class='modal-dialog row justify-content-center'>
                        <img src="/foto/{{$visitante->urlFoto}}" width='400px' height='400px'>
                    </div>
                </div>

            @endforeach
         @endif

        </tbody>

    </table>

    @if (isset($visitantes))
        {{$visitantes->links()}}
    @endif

                </div>

            </div>
        </div>
    </div>

@endguest

@endsection
