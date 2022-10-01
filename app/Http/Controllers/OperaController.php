<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\DataLayer;

class OperaController extends Controller
{
    public function show($id) {
        
        session_start(); //fa partire la sessione e rimanda alla view index
        $dl = new DataLayer();

        //controlla che sia loggato
        if(isset($_SESSION['logged'])) {
            
        } else {
            $opera = $dl->getOperaByID($id);
            return view('opere.opera')->with('opera',$opera);
        }
        
        
        
        
        
        
    }
}
