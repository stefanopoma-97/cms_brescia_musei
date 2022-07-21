<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\DataLayer;

class OperaController extends Controller
{
    public function show($id) {
        
        $dl = new DataLayer();
        /*$user_id = $dl->getUserID($_SESSION['loggedName']);
        if($user_id==-1){
            session_destroy();
            return Redirect::to(route('user.auth.login'));
        }*/
        $opera = $dl->getOperaByID($id);
            
        
        return view('opere.opera')->with('opera',$opera);
        
        
        
    }
}
