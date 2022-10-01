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
            $array = $dl->getOperaByID($id);
            $opera = $array[0];
            $autore = $array[1];
            $visite = $array[2];
            $tempo = $array[3];
            return view('opere.opera')->with('opera',$opera)
                    ->with('autore',$autore)
                    ->with('visite',$visite)
                    ->with('tempo',$tempo);
        }
        
        
        
        
        
        
    }
}
