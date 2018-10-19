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
                <div class="card-header">{{ __('Cadastrar Visitante') }}</div>

                <div class="card-body">
                    <form id='form' method="POST" action="{{ url("/salvarVisitante") }}" enctype="multipart/form-data" >
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nome') }}</label>

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


                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Foto') }}</label>

                            <div class="ol-md-4">
                                <input style="background-color: transparent; border-color: transparent; color: black;" type="file" class="form-control" id="foto" name="foto" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">{{ __('Cadastrar Visita Agora ?') }}</label>

                            <div class="col-md-4">
                                <input type="checkbox" name="cadastrar" value="sim"> Sim
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

        $("#numeroDoc").attr('maxlength',11);
        $("#numeroDoc").mask('000.000.000-00', {reverse: true});

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
