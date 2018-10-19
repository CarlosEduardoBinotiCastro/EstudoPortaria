<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Visitante;
use App\Models\Visita;


class VisitanteController extends Controller
{
    private $paginacao = 5;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listarVisitantesPorFiltroParaViewPost(Request $request){

        if($request->dado != null || !empty($request->dado)){
            return redirect()->route('listarVisitanteFiltro', ['nomeOuDocumento' => $request->dado]);
        }else{
            return redirect()->route('listarVisitante');
        }
    }

    public function listarVisitantesPorFiltroParaViewData(Request $request){
        //   verifica se possui outro campo no request. Necessario na comparação de duas datas
        return redirect()->route('relatorioVisitanteFiltroData', ['dataInicio' => $request->dado, 'dataFinal' => $request->dado2]);
    }

    public function listarVisitantesPorFiltroParaViewGet(){
        return view('visitantes.listarVisitantes', ['visitantes' => $this->listarVisitantes()]);
    }

    public function listarVisitanteFiltro($nomeOuDoc){
        $visitantes = Visitante::orderBy('nomeVisitante');
        $visitantes->select('visitante.*');

            if(!is_numeric($nomeOuDoc)){
                $arrayPalavras = explode(' ', $nomeOuDoc);
                foreach ($arrayPalavras as $palavra) {
                    $visitantes->where('nomeVisitante', 'like', '%' . $palavra . '%');
                }
            }else{
                $visitantes->where('numeroDoc', 'like', "%".$nomeOuDoc."%");
            }

        $visitantes = $visitantes->paginate($this->paginacao);
        return view('visitantes.listarVisitantes', ['visitantes' => $visitantes]);
    }

    public function listarVisitantes(){
        $visitantes = Visitante::orderBy('nomeVisitante');
        $visitantes->select('*');
        return $visitantes = $visitantes->paginate($this->paginacao);
    }

    public function pegarFoto($foto){
        $file_path = storage_path("app/".$foto);
        return response()->file($file_path);
    }

    public function listarVisitanteFiltroData($dataInicio, $dataFinal){

        $visitantes = Visita::orderBy('visitante.nomeVisitante');
        $visitantes->join('visitante', 'visita.visitanteID', 'visitante.visitanteID');
        $visitantes->join('local', 'visita.localID', 'local.localID');
        $visitantes->select('visita.*', 'visitante.*', 'local.nomeLocal');
        $visitantes->whereBetween('dataHora',  [$dataInicio . ' 00:00:01', $dataFinal . ' 23:59:59']);
        $visitantes = $visitantes->paginate($this->paginacao);
        return view('relatorios.listarVisitanteData', ['visitantes' => $visitantes]);
    }

    public function salvarVisitante(Request $request){
        //dd(storage_path());

        $name = explode('.', $request->foto->getClientOriginalName());
        $nameFile = $request->numeroDoc.kebab_case($request->name).".".$name[1];

        $validar = $this->validar($request);

        switch ($validar){
            case 1:
                return redirect()->back()->with("erro", "CPF invalido")->withInput();
            break;

            case 2:
                return redirect()->back()->with("erro", "RG invalido")->withInput();
            break;

            case 3:
                return redirect()->back()->with("erro", "Arquivo de foto invalido: Somente JPG e PNG")->withInput();
            break;

            case 4:
                return redirect()->back()->with("erro", "Documento ja cadastrado")->withInput();
            break;

            default:
                $insert = Visitante::orderBy('nomeVisitante');
                $insert->insert(['nomeVisitante' => $request->name, 'tipoDoc' => $request->tipoDoc, 'numeroDoc' => $request->numeroDoc, 'urlFoto' => $nameFile, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
                $request->foto->storeAs("", $nameFile);

                if(isset($request->cadastrar)){
                    $visitanteID = Visitante::orderBy('nomeVisitante')->max('visitanteID');
                    return redirect()->route('cadastrarVisita', ['dadoVisitante' => $visitanteID]);
                }else{
                    return redirect('/carregarHome')->with("sucesso", "Visitante Cadastrado");
                }
            break;
        }

    }

    public function validar($request){
        if($request->tipoDoc == 'CPF'){
            if(!$this->validaCPF($request->numeroDoc)){
                return 1;
            }
        }else{
            if(strlen($request->numeroDoc) < 5){
                return 2;
            }
        }

        if($request->foto != null){
            $name = explode('.', $request->foto->getClientOriginalName());
            if($name[1] != 'jpg' && $name[1] != 'png'){
                return 3;
            }
        }

        $numeroDocBD =Visitante::orderBy('nomeVisitante')->select('numeroDoc')->where('visitanteID', '=', $request->visitanteID)->first();
        if($numeroDocBD != null){
            if(Visitante::orderBy('nomeVisitante')->select('*')->where('numeroDoc', '=', $request->numeroDoc)->get()->count() && $numeroDocBD->numeroDoc != $request->numeroDoc){
                return 4;
            }
        }else{
            if(Visitante::orderBy('nomeVisitante')->select('*')->where('numeroDoc', '=', $request->numeroDoc)->get()->count()){
                return 4;
            }
        }


        return 5;
    }

    public function carregarCadastro(){
        return view('visitantes.cadastrarVisitante');
    }

    public function carregarInformacoesVisitante($visitanteID){

        $dadosVisitante = Visitante::orderBy('nomeVisitante');
        $dadosVisitante->select('*');
        $dadosVisitante->where('visitanteID', '=', $visitanteID);
        $dadosVisitante = $dadosVisitante->first();

        $visitas = Visita::orderBy('visitaID');
        $visitas->join('local', 'visita.localID', 'local.localID');
        $visitas->select('visita.*', 'local.nomeLocal');
        $visitas->where('visitanteID', '=', $visitanteID);
        $visitas = $visitas->paginate($this->paginacao);

        return view('visitantes.informacoesVisitante', ['visitas' => $visitas, 'dadosVisitante' => $dadosVisitante]);
    }

    public function editarVisitante(Request $request){
        $visitante = Visitante::orderBy('nomeVisitante');

        $validar = $this->validar($request);

        switch ($validar){
            case 1:
                return redirect()->back()->with("erro", "CPF invalido")->withInput();
            break;

            case 2:
                return redirect()->back()->with("erro", "RG invalido")->withInput();
            break;

            case 3:
                return redirect()->back()->with("erro", "Arquivo de foto invalido: Somente JPG e PNG")->withInput();
            break;

            case 4:
                return redirect()->back()->with("erro", "Documento ja cadastrado")->withInput();
            break;

            default:
                if($request->foto != null){
                    $name = explode('.', $request->foto->getClientOriginalName());
                    $nameFile = $request->numeroDoc.kebab_case($request->name).".".$name[1];
                    $visitante->where('visitanteID', '=', $request->visitanteID)->update(['nomeVisitante' => $request->nomeVisitante, 'tipoDoc' => $request->tipoDoc, 'numeroDoc' => $request->numeroDoc, 'urlFoto' => $nameFile]);
                    $request->foto->storeAs("", $nameFile);
                }else{
                    $visitante->where('visitanteID', '=', $request->visitanteID)->update(['nomeVisitante' => $request->nomeVisitante, 'tipoDoc' => $request->tipoDoc, 'numeroDoc' => $request->numeroDoc]);
                }
                return redirect('/carregarHome')->with("sucesso", "Visitante Editado Com Sucesso");
            break;
        }

    }

    public function deletarVisitante($visitanteID){
        if($this->validarDeletar($visitanteID)){
            return redirect()->back()->with('erro', 'Este visitante está vinculado com alguma visita, impossivel deletar!');
        }else{
            $visitante = Visitante::orderBy('nomeVisitante')->where('visitanteID', '=', $visitanteID)->delete();
            return redirect('/listarVisitante');
        }
    }

    public function validarDeletar($visitanteID){
        $visitante = Visita::orderBy('visitaID');
        $visitante->select('*');
        $visitante->where('visitanteID', '=', $visitanteID);
        return $visitante = $visitante->count();
    }

    function validaCPF($cpf = null) {

        // Verifica se um número foi informado
        if(empty($cpf)) {
            return false;
        }



        // Verifica se o numero de digitos informados é igual a 11
        if (strlen($cpf) != 11) {
            return false;
        }
        // Verifica se nenhuma das sequências invalidas abaixo
        // foi digitada. Caso afirmativo, retorna falso
        else if ($cpf == '00000000000' ||
            $cpf == '11111111111' ||
            $cpf == '22222222222' ||
            $cpf == '33333333333' ||
            $cpf == '44444444444' ||
            $cpf == '55555555555' ||
            $cpf == '66666666666' ||
            $cpf == '77777777777' ||
            $cpf == '88888888888' ||
            $cpf == '99999999999') {
            return false;
         // Calcula os digitos verificadores para verificar se o
         // CPF é válido
         } else {

            for ($t = 9; $t < 11; $t++) {

                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf{$c} != $d) {
                    return false;
                }
            }

            return true;
        }
    }

}
