<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Faker\Provider\DateTime;
use Illuminate\Support\Facades\Cookie;
use App\Models\Visitante;
use App\Models\Visita;
use App\Models\Local;

class VisitaController extends Controller
{
    private $paginacao = 5;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function chamarListarVisitas(Request $request){
        return redirect()->route('listarVisitas', ['dataInicio' => $request->dataInicio, 'dataFinal' => $request->dataFinal]);
    }

    public function listarVisitas($dataInicio = null, $dataFinal = null){

        $visitas = Visita::orderBy('visitante.nomeVisitante');
        $visitas->join('visitante', 'visita.visitanteID', '=', 'visitante.visitanteID');
        $visitas->join('local', 'visita.localID', '=', 'local.localID');
        $visitas->join('users', 'visita.userID', '=', 'users.id');
        $visitas->select('visita.*', 'local.nomeLocal', 'visitante.nomeVisitante', 'visitante.urlFoto', 'visitante.tipoDoc', 'visitante.numeroDoc', 'users.name as porteiro', 'visitante.visitanteID');

        if($dataInicio  != null && $dataFinal  != null){
            $visitas->whereBetween('dataHora',  [$dataInicio . ' 00:00:01', $dataFinal . ' 23:59:59']);
        }

        $visitas = $visitas->paginate($this->paginacao);

        return view('visitas.listarVisitas', ['visitas' => $visitas]);
    }

    public function registrarSaida($visitaID){
        $visitasAtiva = Visita::orderBy('visitaID')->where('visitaID', '=', $visitaID)->update(['dataHoraSaida' => date('Y-m-d H:i:s')]);
        return redirect('/carregarHome');
    }


    public function carregarVisitasAtivas(){
        $visitasAtivas = Visita::orderBy('visitante.nomeVisitante');
        $visitasAtivas->join('visitante', 'visita.visitanteID', '=', 'visitante.visitanteID');
        $visitasAtivas->join('local', 'visita.localID', '=', 'local.localID');
        $visitasAtivas->select('visita.*', 'local.nomeLocal', 'visitante.nomeVisitante', 'visitante.urlFoto', 'visitante.tipoDoc', 'visitante.numeroDoc', 'visitante.visitanteID');
        $visitasAtivas->where('dataHoraSaida', '=', null);
        $visitasAtivas = $visitasAtivas->paginate($this->paginacao);
        return $visitasAtivas;
    }


    public function cadastrarVisita($visitanteID){

        $dadoVisitante = Visitante::orderBy('nomeVisitante')->select('*')->where('visitanteID', '=', $visitanteID)->first();
        $locais = Local::orderBy('nomeLocal')->select('*')->get();

        $visitasAtivas = $this->carregarVisitasAtivas();

        $crachasUsados = array();
        $crachas = array();

        for($i = 1; $i <= 30; $i++ ){
            array_push($crachas, $i);
        }

        if($visitasAtivas != null){
            foreach($visitasAtivas as $visitaAtiva){
                array_push($crachasUsados, $visitaAtiva->numeroCracha);
            }

            foreach($crachasUsados as $crachaUsado){
                unset($crachas[array_search($crachaUsado, $crachas)]);
            }
        }
        return view('visitas.cadastrarVisita', ['crachas' => $crachas, 'locais' => $locais, 'dadoVisitante' => $dadoVisitante]);
    }

    public function registrarVisita(Request $request){
        $insert = Visita::orderBy('visitaID');
        $data = str_replace('/', "-", $request->dataHora);

        $validar = $this->validar($request);
        switch ($validar){
            case 1:
                return redirect()->back()->with("erro", "Data Invalida")->withInput();
            break;

            case 2:
                return redirect()->back()->with("erro", "Essa pessoa ja esta em uma visita")->withInput();
            break;

            default:
                $insert->insert(['VisitanteID' => $request->visitanteID, 'localID' => $request->local, 'userID' => Auth::user()->id, 'numeroCracha' => $request->cracha, 'dataHora' => date('Y-m-d H:i:s',  strtotime($data)),'visitado' => $request->visitado, 'assunto' => $request->assunto ,'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
                return redirect('/carregarHome');
            break;
        }
    }

    public function validar($request){

        $visita = Visita::orderBy('visitaID')->select('*')->where('visitaID', '=', $request->visitaID)->first();

        if($visita == null){
            if(strlen($request->dataHora) < 16){
                return 1;
            }

            if(Visita::orderBy('visitaID')->select('*')->where('dataHoraSaida', '=', null)->where('visitanteID', '=', $request->visitanteID)->get()->count()){
                return 2;
            }
        }else{
            if(strlen($request->dataHora) < 16){
                return 1;
            }

        }

    }

    public function informacoesVisita($visitaID){

        $visita = Visita::orderBy('visitaID')->select('*')->where('visitaID', '=', $visitaID)->first();
        $dadoVisitante = Visitante::orderBy('nomeVisitante')->select('*')->where('visitanteID', '=', $visita->visitanteID)->first();
        $locais = Local::orderBy('nomeLocal')->select('*')->get();

        $crachas = array();
        for($i = 1; $i <= 30; $i++ ){
            array_push($crachas, $i);
        }

        return view('visitas.informacoesVisita', ['crachas' => $crachas, 'locais' => $locais, 'dadoVisitante' => $dadoVisitante, 'visita' => $visita]);
    }


    public function editarVisita(Request $request){

        $visita = Visita::orderBy('visitaID');
        $validar = $this->validar($request);

        switch ($validar){
            case 1:
                return redirect()->back()->with("erro", "Data Invalida")->withInput();
            break;

            case 2:
                return redirect()->back()->with("erro", "Essa pessoa ja esta em uma visita")->withInput();
            break;

            default:
                $dataHora = str_replace('/', "-", $request->dataHora);
                $dataHoraSaida = str_replace('/', "-", $request->dataHoraSaida);
                $visita->where('visitaID', '=', $request->visitaID);
                $visita->update(['visitanteID' => $request->visitanteID, 'localID' => $request->local, 'userID' => Auth::user()->id, 'numeroCracha' => $request->numeroCracha, 'dataHora' => date('Y-m-d H:i:s',  strtotime($dataHora)),'dataHoraSaida' => date('Y-m-d H:i:s',  strtotime($dataHoraSaida)),'visitado' => $request->visitado, 'assunto' => $request->assunto ,'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
                return redirect('/carregarHome')->with('sucesso', 'Visita Editada');
            break;
        }

    }

}
