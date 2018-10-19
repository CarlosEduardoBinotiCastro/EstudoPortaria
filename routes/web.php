<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/carregarHome');
});

Auth::routes();

// RELACIONADO COM A LISTAGEM DAS VISITAS ATIVAS

Route::get('/carregarHome', 'HomeController@carregarHome');

Route::get('/foto/{foto}', 'VisitanteController@pegarFoto');
Route::get('/registrarSaida/{visitaID}', 'VisitaController@registrarSaida');

// RELACIONADO OS VISITANTES

Route::post('/pesquisarVisitanteFiltro', 'VisitanteController@listarVisitantesPorFiltroParaViewPost');
Route::get('/listarVisitanteFiltro/filtro/{nomeOuDocumento}', ['as'=> 'listarVisitanteFiltro', 'uses'=>'VisitanteController@listarVisitanteFiltro']);
Route::get('/listarVisitante', 'VisitanteController@listarVisitantesPorFiltroParaViewGet')->name('listarVisitante');;

Route::get('/cadastrarVisitante', 'VisitanteController@carregarCadastro');
Route::post('/salvarVisitante', 'VisitanteController@salvarVisitante');
Route::post('/editarVisitante', 'VisitanteController@editarVisitante');

Route::get('/informacoesVisitante/{visitanteID}', 'VisitanteController@carregarInformacoesVisitante');
Route::get('/deletarVisitante/{visitanteID}', 'VisitanteController@deletarVisitante');

// RELACIONADO COM OS RELATORIOS DOS VISITANTES

Route::post('/relatorioVisitanteData', 'VisitanteController@listarVisitantesPorFiltroParaViewData');
Route::get('/relatorioVisitanteFiltroData/dataInicio/{dataInicio}/dataFinal/{dataFinal}', ['as' => 'relatorioVisitanteFiltroData', 'uses' => 'VisitanteController@listarVisitanteFiltroData']);
Route::get('/relatorioVisitanteData', function(){
        return view('relatorios.listarVisitanteData');
});

// RELACIONADO COM AS VISITAS

Route::get('/cadastrarVisita/{dadoVisitante}', ['as'=> 'cadastrarVisita', 'uses'=>'VisitaController@CadastrarVisita']);
Route::post('/registrarVisita', 'VisitaController@registrarVisita');

Route::get('/listarVisitas', 'VisitaController@listarVisitas');
Route::get('/listarVisitas/{dataInicio}/{dataFinal}', ['as' => 'listarVisitas', 'uses' => 'VisitaController@listarVisitas']);
Route::post('/chamarListarVisitas', 'VisitaController@chamarListarVisitas');

Route::get('/informacoesVisita/{visitaID}', 'VisitaController@informacoesVisita');
Route::post('/editarVisita', 'VisitaController@editarVisita');

// RELACIONADO COM  OS LOCAIS

Route::get('/listarLocais', 'LocalController@listarLocais');
Route::get('/listarLocais/{nome}', ['as' => 'listarLocais', 'uses' => 'LocalController@listarLocais']);
Route::post('/chamarListarLocais', 'LocalController@chamarListarLocais');

Route::post('/salvarLocal', 'LocalController@salvarLocal');
Route::get('/cadastrarLocal', function (){
    if (Auth::user()->idGrupo == 1){
        return view('locais.cadastrarLocal');
    }else{
        return redirect('/carregarHome');
    }
});

Route::get('/deletarLocal/{localID}', 'LocalController@deletarLocal');
Route::get('/informacoesLocal/{localID}', 'LocalController@informacoesLocal');
Route::post('/editarLocal', 'LocalController@editarLocal');



// RELACIONADO COM  OS USUARIOS

Route::get('/register/registrar', 'Auth\RegisterController@carregarRegistros');
Route::post('/register/salvar', 'Auth\RegisterController@salvarUsuario');

Route::get('/listarUsuarios', 'UserController@listarUsuarios');
Route::get('/listarUsuarios/{nomeOuDoc}', ['as' => 'listarUsuarios', 'uses' => 'UserController@listarUsuarios']);
Route::post('/chamarListarUsuarios', 'UserController@chamarListarUsuarios');

Route::get('/informacoesUsuario/{id}', 'UserController@carregarInformacoesUsuario');
Route::post('/editarUsuario', 'UserController@editarUsuario');

Route::get('/desativarUsuario/{id}', 'UserController@desativarUsuario');
Route::get('/deletarUsuario/{id}', 'UserController@deletarUsuario');

//RELACIONADO COM RELATORIO QUANTITATIVO SETOR

Route::get('/relatorioQuantitativoSetor', function(){
    return view('relatorios.quantitativoSetor');
});
Route::post('/relatorioQuantitativoSetor', 'LocalController@relatorioQuantitativoSetor');

Route::get('/gerarPdfQuantitativo', function(){
        $pdf = Session::get('pdf');
        return $pdf->stream();
});
