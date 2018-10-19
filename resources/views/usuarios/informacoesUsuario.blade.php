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
                <div class="card-header">{{ __('Informações') }}</div>

                <div class="card-body">
                    <form id='form' method="POST" action="{{ url("/editarUsuario") }}" >
                        @csrf
                    <input type="hidden" name="id" id="id" value="{{$usuario->id}}">
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{$usuario->name}}" minlength="4" required autofocus>

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
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $usuario->email }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">{{ __('ALterar Senha ?') }}</label>

                            <div class="col-md-4">
                                <input id="alterarSenha" type="checkbox" name="alterarSenha" value="sim"> SIM
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
                                    <option @if ($grupo->idGrupo == $usuario->idGrupo) selected @endif value="{{$grupo->idGrupo}}"> {{$grupo->nome}} </option>
                                @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                                <label for="status" class="col-md-4 col-form-label text-md-right">{{ __('Status') }}</label>

                                <div class="col-md-6">
                                    <select class="custom-select mr-sm-2" name="status" id="status" required>
                                    <option @if ($usuario->status == 'Ativo') selected @endif value="Ativo">Ativo</option>
                                    <option @if ($usuario->status == 'Desativado') selected @endif value="Desativado">Desativado</option>
                                    </select>
                                </div>
                        </div>

                        <div class="form-group row">
                            <label for="tipoDoc" class="col-md-4 col-form-label text-md-right">{{ __('Documento') }}</label>

                            <div class="col-md-2">
                                <select class="custom-select mr-sm-2" name="tipoDoc" id="tipoDoc">
                                <option @if ($usuario->tipoDoc == 'CPF') selected @endif value="CPF">CPF</option>
                                <option @if ($usuario->tipoDoc == 'RG') selected @endif value="RG">RG</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                            <input id="numeroDoc" type="text" class="form-control numeroDoc" name="numeroDoc" value="{{$usuario->numeroDoc}}" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Editar') }}
                                </button>
                                <a class="btn btn-primary" href="/listarUsuarios"> Voltar </a>
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

        if($("#tipoDoc").val() == 'CPF'){
            $("#numeroDoc").attr('maxlength',11);
            $("#numeroDoc").mask('000.000.000-00');
        }else{
            $("#numeroDoc").unmask();
            $("#numeroDoc").attr('maxlength',10);
        }


        $("#password").prop('disabled', true);
        $("#password-confirm").prop('disabled', true);

        $(document).on('change','#tipoDoc',function(){
        if($("#tipoDoc").val() == 'CPF'){
            $("#numeroDoc").val(null);
            $("#numeroDoc").attr('maxlength',11);
            $("#numeroDoc").mask('000.000.000-00', {reverse: true});
        }else{
            $("#numeroDoc").val(null);
            $("#numeroDoc").unmask();
            $("#numeroDoc").attr('maxlength',10);
        }
        });

        $(document).on('click','#alterarSenha',function(){
            if($("#password").is(":disabled")){
                $("#password").prop('disabled', false);
                $("#password-confirm").prop('disabled', false);
            }else{
                $("#password").val(null);
                $("#password-confirm").val(null);
                $("#password").prop('disabled', true);
                $("#password-confirm").prop('disabled', true);
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
