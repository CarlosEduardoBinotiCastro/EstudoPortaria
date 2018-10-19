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
                <div class="card-header">Perfil Do Visitante</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status')}}
                        </div>
                    @endif

                    <div class="col-md-12">
                        <form action="{{url("/pesquisarVisitanteFiltro")}}" method="POST">
                            @csrf
                            <div style="float: left;" class="col-md-4">
                                    <label><strong> N° de Documento ou Nome </strong></label>
                            </div>
                            <div style="float: left;" class="col-md-5">
                                <input class="form-control" type="text" name="dado">
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
            <h4>Visitantes Cadastrados</h4>
            <div class="table-responsive">


                  <table id="mytable" class="table table-bordred table-striped">

                       <thead>

                       <th>Foto</th> <!-- Foto 50 px por 50 px -->
                       <th>Nome</th>
                        <th>Tipo Do Documento</th>
                         <th>Número Do Documento</th>
                         <th>Editar</th>
                         <th>Cadastrar Visita</th>
                         @if (Auth::user()->idGrupo == 1)
                            <th>Deletar</th>
                         @endif
                       </thead>
        <tbody>

         @foreach ($visitantes as $visitante)

         @php
            if($visitante->tipoDoc == 'CPF'){
                    $mask = "###.###.###-##";
                    $visitante->numeroDoc = str_replace(" ","",$visitante->numeroDoc);

                    for($i=0;$i<strlen($visitante->numeroDoc);$i++){
                        $mask[strpos($mask,"#")] = $visitante->numeroDoc[$i];
                    }
                    $visitante->numeroDoc = $mask;
                }
        @endphp

         <tr>
                <?php // echo "<td><img src='/foto/".$visitantes->urlFoto."' height='50px' width='50px'></td>" ?>
         <td><img id="myImg" src="/foto/{{$visitante->urlFoto}}" height="50px" width="50px" data-toggle="modal" data-target="#myModal{{$visitante->visitanteID}}f"></td>
            <td>{{$visitante->nomeVisitante}}</td>
            <td>{{$visitante->tipoDoc}}</td>
            <td>{{$visitante->numeroDoc}}</td>
         <td><a href='/informacoesVisitante/{{$visitante->visitanteID}}' class="btn btn-primary">Editar</a></td>
            <td><a href='/cadastrarVisita/{{$visitante->visitanteID}}' class="btn btn-primary">Cadastrar Visita</a></td>
            @if (Auth::user()->idGrupo == 1) <td><a href='' class="btn btn-danger" data-toggle="modal" data-target="#myModal{{$visitante->visitanteID}}">Deletar</a></td> @endif
            </tr>

            <div class='modal fade' id="myModal{{$visitante->visitanteID}}f" role='dialog'>
                <div class='modal-dialog row justify-content-center'>
                    <img src="/foto/{{$visitante->urlFoto}}" width='400px' height='400px'>
                </div>
            </div>

            @if (Auth::user()->idGrupo == 1)
                <div class="modal" tabindex="-1" role="dialog" id="myModal{{$visitante->visitanteID}}">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title">Apagar Visitante</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <p><Strong>Visitante:</Strong> {{$visitante->nomeVisitante}} </p>
                              <p style="text-transform: uppercase;"> <strong> Deseja realmente apagar o Visitante ? </strong> </p>
                            </div>
                            <div class="modal-footer">
                              <a href='/deletarVisitante/{{$visitante->visitanteID}}' class="btn btn-danger">Apagar</a>
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            </div>
                          </div>
                        </div>
                      </div>
            @endif

         @endforeach

        </tbody>

    </table>

    {{$visitantes->links()}}

                </div>

            </div>
        </div>
    </div>

@endguest

@endsection
