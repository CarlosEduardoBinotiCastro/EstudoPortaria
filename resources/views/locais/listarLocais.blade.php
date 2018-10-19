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
                <div class="card-header">Pesquisar Setor</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status')}}
                        </div>
                    @endif

                    <div class="col-md-12">
                        <form action="{{url("/chamarListarLocais")}}" method="POST">
                            @csrf
                            <div style="float: left;" class="col-md-4">
                                    <label><strong>Nome</strong></label>
                            </div>
                            <div style="float: left;" class="col-md-5">
                                <input class="form-control" type="text" name="nome">
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
            <h4>Locais Cadastrados</h4>
            <div class="table-responsive">


                  <table id="mytable" class="table table-bordred table-striped">

                       <thead>
                       <th>Nome</th>
                       <th>Telefone</th>
                        @if (Auth::user()->idGrupo == 1) <th>Editar</th> @else <th>Ver</th> @endif
                        @if (Auth::user()->idGrupo == 1) <th>Apagar</th> @endif
                       </thead>
        <tbody>

         @foreach ($locais as $local)

         <tr>
            <td>{{$local->nomeLocal}}</td>
            <td>{{$local->telefone}}</td>
            @if (Auth::user()->idGrupo == 1) <td><a href='/informacoesLocal/{{$local->localID}}' class="btn btn-primary">Editar</a></td> @else <td><a href='/informacoesLocal/{{$local->localID}}' class="btn btn-primary">Ver</a></td> @endif
                @if (Auth::user()->idGrupo == 1) <td><a href='' class="btn btn-danger" data-toggle="modal" data-target="#myModal{{$local->localID}}">Apagar</a></td> @endif
        </tr>

        @if (Auth::user()->idGrupo == 1)
            <div class="modal" tabindex="-1" role="dialog" id="myModal{{$local->localID}}">
                    <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title">Apagar Setor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                        <p><Strong>Setor:</Strong> {{$local->nomeLocal}} </p>
                        <p style="text-transform: uppercase;"> <strong> Deseja realmente apagar o Setor ? </strong> </p>
                        </div>
                        <div class="modal-footer">
                        <a href='/deletarLocal/{{$local->localID}}' class="btn btn-danger">Apagar</a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                    </div>
                </div>
        @endif
         @endforeach

        </tbody>

    </table>

    {{$locais->links()}}

                </div>

            </div>
        </div>
    </div>

@endguest

@endsection
