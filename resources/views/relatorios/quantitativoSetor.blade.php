@extends('layouts.app')

@section('content')

@guest

@else

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Relatorio Quantitativo Por Setor</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status')}}
                        </div>
                    @endif

                    <div>
                        <form action="{{url("/relatorioQuantitativoSetor")}}" method="POST">
                            @csrf
                            <div class="form-group row offset-md-2">
                                <label class="col-md-3 text-md-right">Data Inicial</label>

                            @if (date('d') > 1)
                                <input class="form-control col-md-6" type="date" name="dataInicio" value="{{  date('Y-m-d', strtotime("-".date('d', strtotime("-1 day"))." day"))}}" required>
                            @else
                                <input class="form-control col-md-6" type="date" name="dataInicio" value="{{ date('Y-m-d') }}" required>
                            @endif

                            </div>

                            <div class="form-group row offset-md-2">
                                <label class="col-md-3 text-md-right">Data Final</label>
                                <input class="form-control col-md-6" type="date" name="dataFinal" value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="form-group row offset-md-4">
                                    <input class=" btn btn-primary col-md-5" type="submit" name="enviar">
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


            <div class="col-md-8 offset-md-2">
            <div class="table-responsive">
                <div class="card">
                    @if(!isset($quantitativoSetor))

                    @else
                        <div class="card-header"><strong>RESULTADO</strong></div>
                        <table id="mytable" class="table table-bordred table-striped">

                            <thead>
                                <th>Setor</th>
                                <th>Quantidade de Visitas</th>

                            </thead>

                            <tbody>

                                @foreach ($quantitativoSetor as $setor)

                                <tr>
                                    <td>{{$setor->nomeLocal}}</td>
                                    <td>{{$setor->quantidade}}</td>
                                </tr>

                                @endforeach

                            </tbody>
                        </table>

                        <div class="offset-md-8">
                        <p><strong> Quantidade de Visitas:  {{$quantitativoVisitas}}</strong></p>
                        <p><strong> Quantidade de Visitantes:  {{$quantitativoVisitantes}}</strong></p>
                        </div>

                    @endif
                </div>
                <br>
                @if(isset($quantitativoSetor))
                <div class="col-md-4 offset-md-8">
                <a class="btn btn-primary" href="/gerarPdfQuantitativo"> Gerar PDF</a>
                </div>
                @endif
                </div>

            </div>
        </div>
    </div>

@endguest

@endsection
