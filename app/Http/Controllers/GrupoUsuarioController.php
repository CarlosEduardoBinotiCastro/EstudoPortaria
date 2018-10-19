<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\GrupoUsuario;

class GrupoUsuarioController extends Controller
{
    public function listar(){
        return GrupoUsuario::orderBy('nome')->select("*")->get();
    }

}
