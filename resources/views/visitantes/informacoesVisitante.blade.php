@extends('layouts.app')

@section('content')

@guest

@else

<div id="ErrorDiv" class="container">
    <div class="col-md-8 offset-md-2">
        @if(session()->has('erro'))
            <br>
            <div class="form-group row mb-0 alert alert-danger" style="font-size:20px">
                {{ session()->get('erro') }}
            </div>
        @endif
        </div>
    </div>
</div>

<div class='modal fade' id="myModal{{$dadosVisitante->visitanteID}}" role='dialog'>
    <div class='modal-dialog row justify-content-center'>
        <img src="/foto/{{$dadosVisitante->urlFoto}}" width='400px' height='400px'>
    </div>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"> <strong>{{ __('Informções do Visitante') }}</strong></div>

                <div class="card-body">
                    <form id='form' method="POST" action="{{ url("/editarVisitante") }}" enctype="multipart/form-data" >
                        @csrf

                        <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                        <img id="myImg" src="/foto/{{$dadosVisitante->urlFoto}}" height="150px" width="150px" data-toggle="modal" data-target="#myModal{{$dadosVisitante->visitanteID}}">
                                </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nome') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="nomeVisitante" value="{{$dadosVisitante->nomeVisitante}}">
                                <input type="hidden" value="{{$dadosVisitante->visitanteID}}" name="visitanteID">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tipoDoc" class="col-md-4 col-form-label text-md-right">{{ __('Documento') }}</label>

                            <div class="col-md-2">
                                <select class="custom-select mr-sm-2" name="tipoDoc" id="tipoDoc">
                                <option @if ($dadosVisitante->tipoDoc == 'CPF') selected @endif value="CPF">CPF</option>
                                <option @if ($dadosVisitante->tipoDoc == 'RG') selected @endif value="RG">RG</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                            <input id="numeroDoc" type="text" class="form-control numeroDoc" name="numeroDoc" value="{{$dadosVisitante->numeroDoc}}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Foto') }}</label>

                            <div class="ol-md-4">
                                <input style="background-color: transparent; border-color: transparent; color: black;" type="file" class="form-control" id="foto" name="foto">
                            </div>
                        </div>

                        <div class="form-group row mb-0 ">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Alterar') }}
                                </button>
                                <a class="btn btn-primary" href="/listarVisitante">Voltar</a>
                            </div>

                        </div>
                    </form>
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
                         <th>Data e Hora da Visita</th>
                         <th>Data e Hora da Saida</th>
                         <th>Numero Do Crachá</th>
                         <th>Local</th>
                         <th>Visitado</th>
                         <th>Assunto</th>
                           <th>Editar</th>
                           <th>Apagar</th>
                       </thead>
        <tbody>

         @foreach ($visitas as $visita)


         @php
         // CONVERSÃO DE CAMPOS DE DATA

         $visita->dataHora = new DateTime($visita->dataHora);
         $visita->dataHoraSaida = new DateTime($visita->dataHoraSaida);

        @endphp

         <tr>
            <td>{{$visita->dataHora->format('d/m/Y H:i:s')}}</td>
            <td>{{$visita->dataHoraSaida->format('d/m/Y H:i:s')}}</td>
            <td>{{$visita->numeroCracha}}</td>
            <td>{{$visita->nomeLocal}}</td>
            <td>{{$visita->visitado}}</td>
            <td>{{$visita->assunto}}</td>
            <td><a href='/informacoesVisita/{{$visita->visitaID}}' class="btn btn-primary">Editar</a></td>
            <td><a href='' class="btn btn-danger" data-toggle="modal" data-target="#myModal{{$visita->visitaID}}">Apagar</a></td>
            </tr>

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


<script type="text/javascript" src="{{ asset('js/jquery.mask.min.js') }}"></script>


<script type="text/javascript">

    $(document).ready(function($) {

        $("#numeroDoc").attr('maxlength',11);
        $("#numeroDoc").mask('000.000.000-00', {reverse: true});

        $(document).on('change','#tipoDoc',function(){
        if(("#tipoDoc").val == 'CPF'){
            $("#numeroDoc").val(null);
            $("#numeroDoc").attr('maxlength',11);
            $("#numeroDoc").mask('000.000.000-00', {reverse: true});
        }else{
            $("#numeroDoc").val(null);
            $("#numeroDoc").unmask();
            $("#numeroDoc").attr('maxlength',10);

        }
        });


        $('#form').validate({
            errorClass: "my-error-class"
        });

        $("#form" ).submit(function( event ) {
            if($("#form").valid()){
                $("#numeroDoc").unmask();
            }
        });
    });

</script>


@endguest

@endsection
