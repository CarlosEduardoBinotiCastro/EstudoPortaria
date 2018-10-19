@extends('layouts.app')

@section('content')

@guest



@else

<div id="ErrorDiv" class="container">
        <div class="col-md-8 offset-md-2">
            @if(session()->has('sucesso'))
                <br>
                <div class="form-group row mb-0 alert alert-success" style="font-size:20px">
                    {{ session()->get('sucesso') }}
                </div>
            @endif
            </div>
        </div>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Pesquisar Visitante</div>

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
            <h4>Visitas Ativas</h4>
            <div class="table-responsive">


                  <table id="mytable" class="table table-bordred table-striped">

                       <thead>

                       <th>Foto</th> <!-- Foto 50 px por 50 px -->
                       <th>Nome</th>
                        <th>Tipo Do Documento</th>
                         <th>Número Do Documento</th>
                         <th>Data e Hora da Visita</th>
                         <th>Numero Do Crachá</th>
                          <th>Local</th>
                           <th>Registrar Saida</th>
                       </thead>
        <tbody>

         @foreach ($visitasAtivas as $visitaAtiva)

            @php
                // CONVERSÃO DE CAMPOS DE DATA
                $visitaAtiva->dataHora = new DateTime($visitaAtiva->dataHora);

                if($visitaAtiva->tipoDoc == 'CPF'){
                    $mask = "###.###.###-##";
                    $visitaAtiva->numeroDoc = str_replace(" ","",$visitaAtiva->numeroDoc);

                    for($i=0;$i<strlen($visitaAtiva->numeroDoc);$i++){
                        $mask[strpos($mask,"#")] = $visitaAtiva->numeroDoc[$i];
                    }
                    $visitaAtiva->numeroDoc = $mask;
                }


            @endphp

         <tr>
                <?php // echo "<td><img src='/foto/".$visitaAtiva->urlFoto."' height='50px' width='50px'></td>" ?>
         <td><img id="myImg" src="/foto/{{$visitaAtiva->urlFoto}}" height="50px" width="50px" data-toggle="modal" data-target="#myModal{{$visitaAtiva->visitaID}}"></td>
         <td> <a style="color: blue;" href="/informacoesVisitante/{{$visitaAtiva->visitanteID}}">{{$visitaAtiva->nomeVisitante}}</a></td>
            <td>{{$visitaAtiva->tipoDoc}}</td>
            <td>{{$visitaAtiva->numeroDoc}}</td>
            <td>{{$visitaAtiva->dataHora->format('d/m/Y H:i:s')}}</td>
            <td>{{$visitaAtiva->numeroCracha}}</td>
            <td>{{$visitaAtiva->nomeLocal}}</td>
         <td><a href='/registrarSaida/{{$visitaAtiva->visitaID}}' class="btn btn-danger">Registar Saida</a></td>
            </tr>

            <div class='modal fade' id="myModal{{$visitaAtiva->visitaID}}" role='dialog'>
                <div class='modal-dialog row justify-content-center'>
                    <img src="/foto/{{$visitaAtiva->urlFoto}}" width='400px' height='400px'>
                </div>
            </div>

         @endforeach



        </tbody>

    </table>

    {{$visitasAtivas->links()}}

                </div>

            </div>
        </div>
    </div>



@endguest



@endsection
