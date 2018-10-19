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
                <div class="card-header">{{ __('Cadastrar Setor') }}</div>

                <div class="card-body">
                    <form id='form' method="POST" action="{{ url("/salvarLocal") }}" enctype="multipart/form-data" >
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nome') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="nomeLocal" value="{{ old('nomeLocal') }}" minlength="4" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tipoDoc" class="col-md-4 col-form-label text-md-right">{{ __('Telefone') }}</label>
                            <div class="col-md-6">
                                <input id="telefone" type="text" class="form-control" name="telefone" value="{{ old('telefone') }}" minlength="2" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Registrar') }}
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

@endsection
