<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Visita;
use App\Models\Visitante;
use App\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{

    private $paginacao = 5;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function chamarListarUsuarios(Request $request){
        return redirect()->route('listarUsuarios', ['nomeOuDoc' => $request->nomeOuDoc]);
    }

    public function listarUsuarios($nomeOuDoc = null){
        if (Auth::user()->idGrupo == 1){
            $usuarios = User::orderBy('name');
            $usuarios->join('grupousuario', 'users.idGrupo', 'grupousuario.idGrupo');
            $usuarios->select('users.*', 'grupousuario.nome as grupoUsuario');

            if(!is_numeric($nomeOuDoc)){
                $arrayPalavras = explode(' ', $nomeOuDoc);
                foreach ($arrayPalavras as $palavra) {
                    $usuarios->where('name', 'like', '%' . $palavra . '%');
                }
            }else{
                $usuarios->where('numeroDoc', 'like', "%".$nomeOuDoc."%");
            }

            $usuarios = $usuarios->paginate($this->paginacao);

            return view('usuarios.listarUsuarios', ['usuarios' => $usuarios]);
        }else{
            return redirect('/carregarHome');
        }
    }


    public function carregarInformacoesUsuario($id){
        if (Auth::user()->idGrupo == 1){
            $grupoUsuarioController = new GrupoUsuarioController();
            $grupoUsuario = $grupoUsuarioController->listar();

            $usuario = User::orderBy('name')->select("*")->where('id', '=', $id)->first();

            return view('usuarios.informacoesUsuario', ['usuario' => $usuario, 'grupoUsuario' => $grupoUsuario]);
        }else{
            return redirect('/carregarHome');
        }

    }

    public function editarUsuario(Request $request){

        $usuario = User::orderBy('name');
        $validar = $this->validar($request);

        switch ($validar){
            case 1:
                return redirect()->back()->with("erro", "Senhas Não Conferem")->withInput();
            break;

            case 2:
                return redirect()->back()->with("erro", "Senha deve possuir no minimo 6 Caracteres")->withInput();
            break;

            case 3:
                return redirect()->back()->with("erro", "CPF invalido")->withInput();
            break;

            case 4:
                return redirect()->back()->with("erro", "RG invalido")->withInput();
            break;

            case 5:
                return redirect()->back()->with("erro", "Documento ja cadastrado")->withInput();
            break;

            case 6:
                return redirect()->back()->with("erro", "email ja cadastrado")->withInput();
            break;

            default:
                $idGrupo = User::orderBy('name')->select("idGrupo")->where('id', '=', $request->id);

                if(isset($request->alterarSenha)){
                    $usuario->where('id', '=', $request->id)->update(['name' => $request->name, 'tipoDoc' => $request->tipoDoc, 'numeroDoc' => $request->numeroDoc, 'email' => $request->email, 'password' => Hash::make($request->password), 'status' => $request->status, 'idGrupo' => $request->idGrupo]) ;
                }else{
                    $usuario->where('id', '=', $request->id)->update(['name' => $request->name, 'tipoDoc' => $request->tipoDoc, 'numeroDoc' => $request->numeroDoc, 'email' => $request->email, 'status' => $request->status, 'idGrupo' => $request->idGrupo]);
                }

                if($request->idGrupo != $idGrupo){
                    Auth::logout();
                    Session::flush();
                }

                return redirect('/carregarHome')->with("sucesso", "Usuario Editado");
                break;
        }

    }

    public function validar($request){

        if(isset($request->alterarSenha)){
            if($request->password != $request->password_confirmation){
                return 1;
            }else{
                if(strlen($request->password) < 6){
                    return 2;
                }
            }
        }

        if($request->tipoDoc == 'CPF'){
            if(!$this->validaCPF($request->numeroDoc)){
                return 3;
            }
        }else{
            if(strlen($request->numeroDoc) < 5){
                return 4;
            }
        }

        $numeroDocBD =User::orderBy('name')->select('numeroDoc')->where('id', '=', $request->id)->first();
        if(Visitante::orderBy('nomeVisitante')->select('*')->where('numeroDoc', '=', $request->numeroDoc)->get()->count() && $numeroDocBD->numeroDoc != $request->numeroDoc){
            return 5;
        }

        $emailDocBD = User::orderBy('name')->select('email')->where('id', '=', $request->id)->first();
        if(User::orderBy('name')->where('email','=',$request->email)->get()->count() && $emailDocBD->email != $request->email){
             return 6;
        }

        return 7;

    }


    public function desativarUsuario($id){
        if (Auth::user()->idGrupo == 1){
            $usuario = User::orderBy('name')->where('id', '=', $id)->update(['status' => 'Desativado']);
            return redirect('/carregarHome')->with('sucesso', 'Usuario Desativado');
        }else{
            return redirect('/carregarHome');
        }
    }


    public function deletarUsuario($id){
        if (Auth::user()->idGrupo == 1){
            if($this->validarDeletar($id)){
                return redirect()->back()->with('erro', 'Este usuário está vinculado com alguma visita, impossivel deletar!');
            }else{
                $usuario = User::orderBy('name')->where('id', '=', $id)->delete();
                return redirect('/carregarHome')->with('sucesso', 'Usuario Apagado');
            }
        }else{
            return redirect('/carregarHome');
        }
    }

    public function validarDeletar($id){
        $usuario = Visita::orderBy('visitaID');
        $usuario->select('*');
        $usuario->where('userID', '=', $id);
        return $usuario = $usuario->count();
    }

    function validaCPF($cpf = null) {

        // Verifica se um número foi informado
        if(empty($cpf)) {
            return false;
        }

        // Elimina possivel mascara
        $cpf = preg_replace("/[^0-9]/", "", $cpf);
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);

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
