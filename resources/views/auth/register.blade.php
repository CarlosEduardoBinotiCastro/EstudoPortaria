@extends('layouts.app')

@section('content')



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
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form id='form' method="POST" action="{{ url("/register/salvar") }}" >
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" minlength="4" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"  minlength="6" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" minlength="6" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="idGrupo" class="col-md-4 col-form-label text-md-right">{{ __('Grupo de Usuario') }}</label>

                            <div class="col-md-6">
                                <select class="custom-select mr-sm-2" name="idGrupo" id="idGrupo" required>
                                @foreach ($grupoUsuario as $grupo)
                                <option value="{{$grupo->idGrupo}}"> {{$grupo->nome}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tipoDoc" class="col-md-4 col-form-label text-md-right">{{ __('Documento') }}</label>

                            <div class="col-md-2">
                                <select class="custom-select mr-sm-2" name="tipoDoc" id="tipoDoc">
                                <option slected value="CPF">CPF</option>
                                <option value="RG">RG</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <input id="numeroDoc" type="text" class="form-control numeroDoc" name="numeroDoc" required>
                            </div>
                        </div>


                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
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


@endsection
