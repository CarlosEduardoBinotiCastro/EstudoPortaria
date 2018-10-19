<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class ControleAcesso extends Model
{

    public function nivelUsuario($nivel, $grupoUsuario){
        switch ($nivel){

            case 'adm':
                if($grupoUsuario == 1){
                    return true;
                }else{
                    return false;
                }
            break;

            default:
                return true;
            break;

        }

    }
}
