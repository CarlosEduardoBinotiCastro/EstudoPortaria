@extends('layouts.app')

@section('content')

@guest

@else

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Pesquisar Visita Por Data</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status')}}
                        </div>
                    @endif

                    <div>
                        <form action="{{url("/chamarListarVisitas")}}" method="POST">
                            @csrf
                            <div class="form-group row offset-md-2">
                                <label class="col-md-3 text-md-right">Data Inicial</label>

                            @if (date('d') > 1)
                                <input class="form-control col-md-6" type="date" name="dataInicio" value="{{  date('Y-m-d', strtotime("-".date('d', strtotime("-1 day"))." day"))}}" required>
                            @else
                                <input class="form-control col-md-6" type="date" name="dataInicio" value="{{ date('Y-m-d') }}" required>
                            @endif

                            </div>

                            <div class="form-group row offset-md-2">
                                <label class="col-md-3 text-md-right">Data Final</label>
                                <input class="form-control col-md-6" type="date" name="dataFinal" value="{{ date('Y-m-d') }}" required>
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
            <h4>Visitas</h4>
            <div class="table-responsive">


                  <table id="mytable" class="table table-bordred table-striped">

                       <thead>

                       <th>Foto</th> <!-- Foto 50 px por 50 px -->
                       <th>Nome</th>
                         <th>Número Do Documento</th>
                         <th>Data e Hora da Visita</th>
                         <th>Data e Hora da Saida</th>
                         <th>Local</th>
                         <th>Assunto</th>
                         <th>Porteiro</th>
                           <th>Editar</th>
                           <th>Apagar</th>
                       </thead>
        <tbody>

         @foreach ($visitas as $visita)


         @php
         // CONVERSÃO DE CAMPOS DE DATA

         $visita->dataHora = new DateTime($visita->dataHora);
         $visita->dataHoraSaida = new DateTime($visita->dataHoraSaida);

         // CONVERSÂO DE CPF

        if($visita->tipoDoc == 'CPF'){
            $mask = "###.###.###-##";
            $visita->numeroDoc = str_replace(" ","",$visita->numeroDoc);

            for($i=0;$i<strlen($visita->numeroDoc);$i++){
                $mask[strpos($mask,"#")] = $visita->numeroDoc[$i];
            }
            $visita->numeroDoc = $mask;
        }
        @endphp

         <tr>
         <td><img id="myImg" src="/foto/{{$visita->urlFoto}}" height="50px" width="50px" data-toggle="modal" data-target="#myModal{{$visita->visitaID}}f"></td>
         <td> <a style="color: blue;" href="/informacoesVisitante/{{$visita->visitanteID}}">{{$visita->nomeVisitante}}</a></td>
            <td>{{$visita->numeroDoc}}</td>
            <td>{{$visita->dataHora->format('d/m/Y H:i:s')}}</td>
            <td>{{$visita->dataHoraSaida->format('d/m/Y H:i:s')}}</td>
            <td>{{$visita->nomeLocal}}</td>
            <td>{{$visita->assunto}}</td>
            <td>{{$visita->porteiro}}</td>
            <td><a href='/informacoesVisita/{{$visita->visitaID}}' class="btn btn-primary">Editar</a></td>
            <td><a href='' class="btn btn-danger" data-toggle="modal" data-target="#myModal{{$visita->visitaID}}">Apagar</a></td>
            </tr>


            <div class='modal fade' id="myModal{{$visita->visitaID}}f" role='dialog'>
                <div class='modal-dialog row justify-content-center'>
                    <img src="/foto/{{$visita->urlFoto}}" width='400px' height='400px'>
                </div>
            </div>


            <div class="modal" tabindex="-1" role="dialog" id="myModal{{$visita->visitaID}}">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Apagar Visita</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <p><Strong>Visitante:</Strong> {{$visita->nomeVisitante}} </p>
                          <p><Strong>Local:</Strong> {{$visita->nomeLocal}} </p>
                          <p><Strong>Data Visita</Strong> {{$visita->dataHora->format('d/m/Y H:i:s')}} </p>
                          <p style="text-transform: uppercase;"> <strong> Deseja realmente apagar a Visita ? </strong> </p>
                        </div>
                        <div class="modal-footer">
                          <a href='/apagarVisita/{{$visita->visitaID}}' class="btn btn-danger">Apagar</a>
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        </div>
                      </div>
                    </div>
                  </div>

         @endforeach



        </tbody>

    </table>

    {{$visitas->links()}}

                </div>

            </div>
        </div>
    </div>

@endguest

@endsection
