<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataLayer;
use Laudis\Neo4j\Authentication\Authenticate;
use Laudis\Neo4j\ClientBuilder;
use Laudis\Neo4j\Contracts\TransactionInterface;

class FrontController extends Controller
{
    public function getHome() {
        
        session_start(); //fa partire la sessione e rimanda alla view index
        $dl = new DataLayer();

        //controlla che sia loggato
        if(isset($_SESSION['logged'])) {
            
        } else {
            $opere = $dl->getOpere();
            $opere_selezionate = [];
            
            return view('index')->with('logged',false);
        }
    }
    
    //non usato
    public function getHomeFilter(Request $request) {
        
        session_start(); //fa partire la sessione e rimanda alla view index
        $dl = new DataLayer();
        
        //controlla che sia loggato
        if(isset($_SESSION['logged'])) {
            
        } else {
            
            error_log("Messaggio");
            $raggruppamento = $request->input('raggruppamento');
            $valore = $request->input('valore_raggruppamento');
            $opere_selezionate = $request->input('opere_selezionate');
            $opere_selezionate_array = json_decode(stripslashes($opere_selezionate),true);
            dump($raggruppamento);
            dump($valore);
            dump($opere_selezionate_array);
            //dump(gettype($opere_selezionate_array));
            
            
            
            $opere_selezionate_array_id = $dl->getIdSelezionate($opere_selezionate_array);
            dump($opere_selezionate_array_id);
            
            
            
            $opere = $dl->getOpereMenoSelezionate($opere_selezionate_array_id);
            dump($opere);
            
            $opere_selezionate = $dl->getOpereSelezionate($opere_selezionate_array_id);
            dump($opere_selezionate);
                  
            
            
            
            return view('index')->with('logged',false)->with('opere',$opere)
                    ->with('opere_selezionate',$opere_selezionate);
        }
    }
    
    
    public function percorsi() {
        
        session_start(); //fa partire la sessione e rimanda alla view index
        $dl = new DataLayer();
        
        $client = ClientBuilder::create()
        ->withDriver('bolt', 'bolt://neo4j:neo4j_cms_brescia@localhost') // creates a bolt driver
        ->withDefaultDriver('bolt')
        ->withFormatter(\Laudis\Neo4j\Formatter\BasicFormatter::create())
        ->build();
        
        $results = ($client->run('MATCH (x:Opera) RETURN x AS opere, x.id AS id'));
        dump($results[0]->get('id'));
        dump(count($results));
        
        
        
        
        
        //controlla che sia loggato
        if(isset($_SESSION['logged'])) {
            
        } else {
            $opere = $dl->getOpere();
            $opere_selezionate = [];
            
            return view('filtro_percorsi')->with('logged',false)->with('opere',$opere)
                    ->with('opere_selezionate',$opere_selezionate);
        }
    }
    
    public function percorsiFiltro(Request $request) {
        
        session_start(); //fa partire la sessione e rimanda alla view index
        $dl = new DataLayer();
        
        //controlla che sia loggato
        if(isset($_SESSION['logged'])) {
            
        } else {
            
            error_log("Messaggio");
            $raggruppamento = $request->input('raggruppamento');
            $valore = $request->input('valore_raggruppamento');
            $opere_selezionate = $request->input('opere_selezionate');
            $opere_selezionate_array = json_decode(stripslashes($opere_selezionate),true);
            dump($raggruppamento);
            dump($valore);
            dump($opere_selezionate_array);
            //dump(gettype($opere_selezionate_array));
            
            
            
            $opere_selezionate_array_id = $dl->getIdSelezionate($opere_selezionate_array);
            dump($opere_selezionate_array_id);
            
            
            
            $opere = $dl->getOpereMenoSelezionate($opere_selezionate_array_id);
            dump($opere);
            
            $opere_selezionate = $dl->getOpereSelezionate($opere_selezionate_array_id);
            dump($opere_selezionate);
                  
            
            
            
            return view('filtro_percorsi')->with('logged',false)->with('opere',$opere)
                    ->with('opere_selezionate',$opere_selezionate);
        }
    }
}
