<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataLayer;

class FrontController extends Controller
{
    public function getHome() {
        
        session_start(); //fa partire la sessione e rimanda alla view index
        $dl = new DataLayer();
        
        //controlla che sia loggato
        if(isset($_SESSION['logged'])) {
            
        } else {
            $opere = $dl->getOpere();
            
            return view('index')->with('logged',false)->with('opere',$opere);
        }
    }
}
