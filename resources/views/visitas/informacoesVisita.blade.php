@extends('layouts.app')
@section('content')

@auth

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

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"> <strong>{{ __('Editar Visita') }}</strong></div>

                <div class="card-body">
                    <form id='form' method="POST" action="{{ url("/editarVisita") }}" >
                        @csrf

                        <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                        <img id="myImg" src="/foto/{{$dadoVisitante->urlFoto}}" height="150px" width="150px">
                                </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nome') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{$dadoVisitante->nomeVisitante}}" disabled>
                                <input type="hidden" value="{{$dadoVisitante->visitanteID}}" name="visitanteID">
                                <input type="hidden" value="{{$visita->visitaID}}" name="visitaID">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="local" class="col-md-4 col-form-label text-md-right">{{ __('Local') }}</label>

                            <div class="col-md-6">
                                <select class="custom-select mr-sm-2" name="local" id="local" required>
                                @foreach ($locais as $local)
                                    <option @if ($visita->localID == $local->localID) selected @endif value="{{$local->localID}}">{{$local->nomeLocal}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                                <label for="visitado" class="col-md-4 col-form-label text-md-right">{{ __('Visitado') }}</label>

                                <div class="col-md-6">
                                    <input id="visitado" type="text" class="form-control" name="visitado" value="{{$visita->visitado}}" minlength="4" required>
                                </div>
                        </div>

                        <div class="form-group row">
                                <label for="assunto" class="col-md-4 col-form-label text-md-right">{{ __('Assunto') }}</label>

                                <div class="col-md-6">
                                    <input id="assunto" type="text" class="form-control" name="assunto" value="{{$visita->assunto}}" minlength="4" required>

                                </div>
                        </div>

                        <div class="form-group row">
                                <label for="cracha" class="col-md-4 col-form-label text-md-right">{{ __('Crachá') }}</label>

                                <div class="col-md-2">
                                    <select class="custom-select mr-sm-2" name="numeroCracha" id="numeroCracha" required>
                                    @foreach ($crachas as $cracha)
                                        <option @if ($visita->numeroCracha == $cracha) selected @endif value="{{$cracha}}">{{$cracha}}</option>
                                    @endforeach
                                    </select>
                                </div>
                        </div>

                        @php
                            $dataHora = new DateTime($visita->dataHora);
                            $dataHoraSaida = new DateTime($visita->dataHoraSaida);
                        @endphp

                        <div class="form-group row">
                                <label for="local" class="col-md-4 col-form-label text-md-right">{{ __('Data Entrada Visita') }}</label>
                                <div class="col-md-6">
                                    <input id="dataHora" class="form-control col-md-6" type="text" name="dataHora" value="{{ $dataHora->format('%d%m%Y%H%i') }}" required>
                                </div>
                        </div>

                        <div class="form-group row">
                            <label for="local" class="col-md-4 col-form-label text-md-right">{{ __('Data Saida Visita') }}</label>
                            <div class="col-md-6">
                                <input id="dataHoraSaida" class="form-control col-md-6" type="text" name="dataHoraSaida" value="{{ $dataHoraSaida->format('%d%m%Y%H%i') }}" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Editar') }}
                                </button>


                                <button type="button" class="btn btn-primary" id="btnVoltar">
                                        {{ __('Voltar') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<br><br>
@endauth


<script type="text/javascript" src="{{ asset('js/jquery.mask.min.js') }}"></script>


<script type="text/javascript">

    $(document).ready(function($) {

        $("#dataHora").attr('maxlength',10);
        $("#dataHora").mask('00/00/0000 00:00');

        $("#dataHoraSaida").attr('maxlength',10);
        $("#dataHoraSaida").mask('00/00/0000 00:00');

        $('#form').validate({
            errorClass: "my-error-class"
        });

        $( "#form" ).submit(function( event ) {
            //$("#numeroDoc").unmask();
        });

        $("#btnVoltar").click(function(){
            window.history.back();
        });
    });

</script>


@endsection
