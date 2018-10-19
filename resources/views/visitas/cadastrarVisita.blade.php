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
                <div class="card-header"> <strong>{{ __('Cadastrar Visita') }}</strong></div>

                <div class="card-body">
                    <form id='form' method="POST" action="{{ url("/registrarVisita") }}" >
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
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="local" class="col-md-4 col-form-label text-md-right">{{ __('Local') }}</label>

                            <div class="col-md-6">
                                <select class="custom-select mr-sm-2" name="local" id="local" required>
                                @foreach ($locais as $local)
                                    <option value="{{$local->localID}}">{{$local->nomeLocal}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                                <label for="visitado" class="col-md-4 col-form-label text-md-right">{{ __('Visitado') }}</label>

                                <div class="col-md-6">
                                    <input id="visitado" type="text" class="form-control" name="visitado" value="{{old('visitado')}}" minlength="4" required>

                                </div>
                        </div>

                        <div class="form-group row">
                                <label for="assunto" class="col-md-4 col-form-label text-md-right">{{ __('Assunto') }}</label>

                                <div class="col-md-6">
                                    <input id="assunto" type="text" class="form-control" name="assunto" value="{{old('assunto')}}" minlength="4" required>

                                </div>
                        </div>

                        <div class="form-group row">
                                <label for="cracha" class="col-md-4 col-form-label text-md-right">{{ __('Crachá') }}</label>

                                <div class="col-md-2">
                                    <select class="custom-select mr-sm-2" name="cracha" id="cracha" required>
                                    @foreach ($crachas as $cracha)
                                        <option value="{{$cracha}}">{{$cracha}}</option>
                                    @endforeach
                                    </select>
                                </div>
                        </div>


                        <div class="form-group row">
                                <label for="local" class="col-md-4 col-form-label text-md-right">{{ __('Data Visita') }}</label>
                                <div class="col-md-6">
                                    <input id="dataHora" class="form-control col-md-6" type="text" name="dataHora" value="{{ date('d-m-Y H:i:s') }}" required>
                                </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Cadastrar') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endauth


<script type="text/javascript" src="{{ asset('js/jquery.mask.min.js') }}"></script>


<script type="text/javascript">

    $(document).ready(function($) {

        $("#dataHora").attr('maxlength',10);
        $("#dataHora").mask('00/00/0000 00:00');

        $('#form').validate({
            errorClass: "my-error-class"
        });

        $( "#form" ).submit(function( event ) {
            //$("#numeroDoc").unmask();
        });
    });

</script>


@endsection
