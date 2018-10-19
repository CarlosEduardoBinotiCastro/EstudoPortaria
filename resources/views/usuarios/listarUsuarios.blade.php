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
                <div class="card-header">Pesquisar Usuario</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status')}}
                        </div>
                    @endif

                    <div class="col-md-12">
                        <form action="{{url("/chamarListarUsuarios")}}" method="POST">
                            @csrf
                            <div style="float: left;" class="col-md-4">
                                    <label><strong>Nome ou Documento</strong></label>
                            </div>
                            <div style="float: left;" class="col-md-5">
                                <input class="form-control" type="text" name="nomeOuDoc">
                            </div>
                            <div style="float: left;" class="col-md-3">
                                <input class="btn btn-primary" type="submit" value="Pesquisar" name="enviar">
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
            <h4>Usuarios Cadastrados</h4>
            <div class="table-responsive">


                  <table id="mytable" class="table table-bordred table-striped">

                       <thead>
                       <th>Nome</th>
                       <th>Tipo Documento</th>
                       <th>Numero Documento</th>
                       <th>Email</th>
                       <th>Grupo Usuario</th>
                       <th>Status</th>
                        <th>Editar</th>
                         <th>Desativar</th>

                       </thead>
        <tbody>

         @foreach ($usuarios as $usuario)


         @php
         // CONVERSÂO DE CPF

         if($usuario->tipoDoc == 'CPF'){
            $mask = "###.###.###-##";
            $usuario->numeroDoc = str_replace(" ","",$usuario->numeroDoc);

            for($i=0;$i<strlen($usuario->numeroDoc);$i++){
                $mask[strpos($mask,"#")] = $usuario->numeroDoc[$i];
            }
            $usuario->numeroDoc = $mask;
            }

        @endphp

         <tr>
            <td>{{$usuario->name}}</td>
            <td>{{$usuario->tipoDoc}}</td>
            <td>{{$usuario->numeroDoc}}</td>
            <td>{{$usuario->email}}</td>
            <td>{{$usuario->grupoUsuario}}</td>
            <td>{{$usuario->status}}</td>
            <td><a href='/informacoesUsuario/{{$usuario->id}}' class="btn btn-primary">Editar</a></td>
            <td><a href='/desativarUsuario/{{$usuario->id}}' class="btn btn-warning">Desativar</a></td>
        </tr>


         @endforeach

        </tbody>

    </table>

    {{$usuarios->links()}}

                </div>

            </div>
        </div>
    </div>

@endguest

@endsection
