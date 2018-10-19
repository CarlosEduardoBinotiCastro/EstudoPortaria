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


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"> <strong>{{ __('Informções do Local') }}</strong></div>

                <div class="card-body">
                    <form id='form' method="POST" action="{{ url("/editarLocal") }}" >
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nome') }}</label>

                            <div class="col-md-6">
                                <input id="nomeLocal" type="text" class="form-control" name="nomeLocal" value="{{$local->nomeLocal}}" required>
                                <input type="hidden" value="{{$local->localID}}" name="localID">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Telefone') }}</label>

                            <div class="col-md-6">
                                <input id="telefone" type="text" class="form-control" name="telefone" value="{{$local->telefone}}" required>
                            </div>
                        </div>


                        <div class="form-group row mb-0 ">
                            <div class="col-md-6 offset-md-4">
                                @if (Auth::user()->idGrupo == 1)
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Alterar') }}
                                    </button>
                                @endif
                                <a class="btn btn-primary" href="/listarLocais">Voltar</a>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<br/><br/>

<script type="text/javascript" src="{{ asset('js/jquery.mask.min.js') }}"></script>


<script type="text/javascript">

    $(document).ready(function($) {


        $('#form').validate({
            errorClass: "my-error-class"
        });

        $("#telefone").attr('maxlength',8);
        $("#telefone").mask('00000000');

        $( "#form" ).submit(function( event ) {
            //$("#numeroDoc").unmask();
        });
    });

</script>


@endguest

@endsection
