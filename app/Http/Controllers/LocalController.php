<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Local;
use App\Models\Visita;
use Illuminate\Foundation\Console\Presets\React;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;


class LocalController extends Controller
{
    private $paginacao = 5;

    public function __construct()
    {
        $this->middleware('auth');

    }

    public function chamarListarLocais(Request $request){
        return redirect()->route('listarLocais', ['nome' => $request->nome]);
    }

    public function listarLocais($nome = null){



        $locais = Local::orderBy('nomeLocal');
        $locais->select('*');

        $nomes = explode(" ", $nome);

        foreach($nomes as $n ){
            $locais->where('nomeLocal', 'like', "%".$n."%");
        }

        $locais = $locais->paginate($this->paginacao);

        return view('locais.listarLocais', ['locais' => $locais]);


    }

    public function salvarLocal(Request $request){

        $table = Local::orderBy('nomeLocal');


        $validar = $this->validar($request);

        switch ($validar){
            case 1:
                return redirect()->back()->with("erro", "Local Ja Cadastrado")->withInput();
            break;

            case 2:
                return redirect()->back()->with("erro", "Telefone Ja Cadastrado")->withInput();
            break;

            default:
                $table->insert(['nomeLocal' => $request->nomeLocal, 'telefone' => $request->telefone, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
                return redirect('/carregarHome')->with("sucesso", "Setor Cadastrado com Sucesso");
            break;
        }

    }

    public function informacoesLocal($localID){

            $local = Local::orderBy('nomeLocal');
            $local->select('*')->where('localID', '=', $localID);
            $local = $local->first();

            return view('locais.informacoesLocal', ['local' => $local]);

    }

    public function editarLocal(Request $request){
        $local = Local::orderBy('nomeLocal');

        $validar = $this->validar($request);

        switch ($validar){
            case 1:
                return redirect()->back()->with("erro", "Local Ja Cadastrado")->withInput();
            break;

            case 2:
                return redirect()->back()->with("erro", "Telefone Ja Cadastrado")->withInput();
            break;

            default:
                $local->where('localID','=', $request->localID)->update(['nomeLocal' => $request->nomeLocal, 'telefone' => $request->telefone]);
                return redirect('/carregarHome')->with('sucesso', 'Local Editado com Sucesso');
            break;
        }
    }

    public function validar($request){

        $local = Local::orderBy('nomeLocal')->select('*')->where('localID', '=', $request->localID)->first();

        if($local != null){
            $nomeLocal = Local::orderBy('nomeLocal')->select('nomeLocal')->where('localID', '=', $request->localID)->first();
            if(Local::orderBy('nomeLocal')->select('*')->where('nomeLocal', '=', $request->nomeLocal)->get()->count() && $nomeLocal->nomeLocal != $request->nomeLocal){
                return 1;
            }

            $telefone = Local::orderBy('nomeLocal')->select('telefone')->where('localID', '=', $request->localID)->first();
            if(Local::orderBy('nomeLocal')->select('*')->where('telefone', '=', $request->telefone)->get()->count() && $telefone->telefone != $request->telefone){
                return 2;
            }

        }else{
            if(Local::orderBy('nomeLocal')->select('*')->where('nomeLocal', '=', $request->nomeLocal)->get()->count()){
                return 1;
            }

            if(Local::orderBy('nomeLocal')->select('*')->where('telefone', '=', $request->telefone)->get()->count()){
                return 2;
            }
        }

    }

    public function deletarLocal($localID){
        if (Auth::user()->idGrupo == 1){
            if($this->validarDeletar($localID)){
                return redirect()->back()->with('erro', 'Este local está vinculado com alguma visita, impossivel deletar!');
            }else{
                $local = Local::orderBy('nomeLocal')->where('localID', '=', $localID)->delete();
                return redirect('/listarLocais');
            }
        }else{
            return redirect('/carregarHome');
        }
    }


    public function relatorioQuantitativoSetor(Request $request){

        $quantitativoSetor = Visita::orderBy('visitaID');
        $quantitativoSetor->join('local', 'local.localID', 'visita.localID');
        $quantitativoSetor->selectRaw('COUNT(*) as quantidade, local.nomeLocal');
        $quantitativoSetor->whereBetween('dataHora',  [$request->dataInicio . ' 00:00:01', $request->dataFinal . ' 23:59:59']);
        $quantitativoSetor->groupBy('local.nomeLocal');
        $quantitativoSetor = $quantitativoSetor->get();

        $quantitativoVisitas = Visita::orderBy('visitaID');
        $quantitativoVisitas->whereBetween('dataHora',  [$request->dataInicio . ' 00:00:01', $request->dataFinal . ' 23:59:59']);
        $quantitativoVisitas = $quantitativoVisitas->count();

        $quantitativoVisitantes = Visita::orderBy('visitaID');
        $quantitativoVisitantes->join('visitante', 'visitante.visitanteID', 'visita.visitanteID');
        $quantitativoVisitantes->whereBetween('dataHora',  [$request->dataInicio . ' 00:00:01', $request->dataFinal . ' 23:59:59']);
        $quantitativoVisitantes = $quantitativoVisitantes->count(DB::raw('DISTINCT visitante.visitanteID'));

        $pdf = SnappyPdf::LoadView('pdfs.pdfQuantitativoSetor', ['quantitativoSetor' => $quantitativoSetor, 'quantitativoVisitas' => $quantitativoVisitas, 'quantitativoVisitantes' => $quantitativoVisitantes, 'dataInicio' => $request->dataInicio, 'dataFinal' => $request->dataFinal]);
        $pdf->setOption('zoom', 1.25);
        $pdf->setOption('viewport-size', '1024x768');

        Session::put('pdf', $pdf);

        return view('relatorios.quantitativoSetor', ['quantitativoSetor' => $quantitativoSetor, 'quantitativoVisitas' => $quantitativoVisitas, 'quantitativoVisitantes' => $quantitativoVisitantes]);
    }


    public function validarDeletar($localID){
        $local = Visita::orderBy('visitaID');
        $local->select('*');
        $local->where('localID', '=', $localID);
        return $local = $local->count();
    }

}
