<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\VisitaController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\User;

class HomeController extends Controller
{
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    protected function index($visitasAtivas)
    {
        return view('home', ['visitasAtivas' => $visitasAtivas]);
    }

    public function carregarHome(){
        $teste = DB::table('users')->select("idGrupo")->where('id', '=', Auth::user()->id)->first();

        Session::put('idGrupo', $teste->idGrupo);
        $visitasController = new VisitaController();
        $visitasAtivas = $visitasController->carregarVisitasAtivas();
        return view('home', ['visitasAtivas' => $visitasAtivas]);
    }
}
