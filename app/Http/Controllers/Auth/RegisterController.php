<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Controllers\GrupoUsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/carregarHome';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function carregarRegistros(){
        if (Auth::user()->idGrupo == 1){
            $grupoUsuarioController = new GrupoUsuarioController();
            $grupoUsuario = $grupoUsuarioController->listar();

            return view('auth.register', compact('grupoUsuario'));
        }else{
            return redirect('/carregarHome');
        }
    }

    public function salvarUsuario(Request $request){

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
                return redirect()->back()->with("erro", "email ja cadastrado")->withInput();
            break;

            case 6:
                return redirect()->back()->with("erro", "Documento ja cadastrado")->withInput();
            break;

            default:
                DB::table('users')->insert(['idGrupo' => $request->idGrupo , 'name' => $request->name, 'password' => Hash::make($request->password), 'email' => $request->email, 'tipoDoc' => $request->tipoDoc, 'numeroDoc' => $request->numeroDoc, 'status' => 'ativo', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);

                return redirect('/carregarHome')->with("sucesso", "Usuario Cadastrado");

                break;
        }

    }


    public function validar($request){

        if($request->password != $request->password_confirmation){

            return 1;
        }else{
            if(strlen($request->password) < 6){
                return 2;
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

        if(DB::table('users')->where('email','=',$request->email)->get()->count()){
             return 5;
        }

        if(DB::table('users')->where('numeroDoc','=',$request->numeroDoc)->get()->count()){
            return 6;
        }

        return 7;

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


