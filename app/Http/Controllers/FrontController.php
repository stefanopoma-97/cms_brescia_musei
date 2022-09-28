<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataLayer;
use Laudis\Neo4j\Authentication\Authenticate;
use Laudis\Neo4j\ClientBuilder;
use Laudis\Neo4j\Contracts\TransactionInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;


class FrontController extends Controller
{
    //arrivo in home page
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
    
    //arrivo pagina creazione percorsi prima volta (GET)
    public function percorsi() {
        //error_log('contoller percorsi');
        //Log::info('contoller percorsi');
        
        session_start(); //fa partire la sessione e rimanda alla view index
        $dl = new DataLayer();
        
        //dump("controller percorsi");
        //controlla che sia loggato
        if(isset($_SESSION['logged'])) {
            
        } else {
            
            
            if (isset($_SESSION['categorie'])){
                $categorie = $_SESSION['categorie'];
            }
            else {
                dump("prendo categorie");
                $categorie = $dl->getCategorie();
                $_SESSION['categorie'] = $categorie;
            }
            
            if (isset($_SESSION['tipologie'])){
                $tipologie = $_SESSION['tipologie'];
            }
            else {
                dump("prendo tipologie");
                $tipologie = $dl->getTipologie();
                $_SESSION['tipologie'] = $tipologie;
            }
            
            if (isset($_SESSION['date'])){
                $date = $_SESSION['date'];
            }
            else {
                dump("prendo date");
                $date = $dl->getDate();
                $_SESSION['date'] = $date;
            }
            
            if (isset($_SESSION['secoli'])){
                $secoli = $_SESSION['secoli'];
            }
            else {
                dump("prendo secoli");
                $secoli = $dl->getSecoli();
                $_SESSION['secoli'] = $secoli;
            }
            
            if (isset($_SESSION['luoghi'])){
                $luoghi = $_SESSION['luoghi'];
            }
            else {
                dump("prendo luoghi");
                $luoghi = $dl->getLuoghi();
                $_SESSION['luoghi'] = $luoghi;
            }
            
            if (isset($_SESSION['autori'])){
                $autori = $_SESSION['autori'];
            }
            else {
                dump("prendo autori");
                $autori = $dl->getAutori();
                $_SESSION['autori'] = $autori;
            }
            
            if (isset($_SESSION['eta'])){
                $eta = $_SESSION['eta'];
            }
            else {
                dump("prendo eta");
                $eta = $dl->getEta();
                $_SESSION['eta'] = $eta;
            }
           
            
            
            $opere = $dl->getOpere();
            $opere_selezionate = [];

            
             $raggruppamenti = ["Qualsiasi", "Tipologia", "Data di creazione", "Secolo", "Luogo di provenienza", "Autore", "Visite totali",
                "Tempo totale delle visite", "Visite nell'ultimo anno", "Tempo totale delle visite dell'ultimo anno",
                "Meno visitate", "Età visitatori", "Categoria visitatori", "Sesso visitatori"];
             
             $raggruppamento = "Qualsiasi";
                         
            return view('filtro_percorsi')->with('logged',false)->with('opere',$opere)
                    ->with('opere_selezionate',$opere_selezionate)
                    ->with('raggruppamento',$raggruppamento)
                    ->with('raggruppamenti',$raggruppamenti)
                    ->with('categorie',$categorie)
                    ->with('tipologie',$tipologie)
                    ->with('date',$date)
                    ->with('secoli',$secoli)
                    ->with('luoghi',$luoghi)
                    ->with('autori',$autori)
                    ->with('eta',$eta)
                    ;
            
            /*return view('filtro_percorsi')->with('logged',false)->with('opere',$opere)
                    ->with('opere_selezionate',$opere_selezionate)
                    ->with('raggruppamento',$raggruppamento)
                    ->with('raggruppamenti',$raggruppamenti)
                    ->with('categorie',[])
                    ->with('tipologie',[])
                    ->with('date',[])
                    ->with('secoli',[])
                    ->with('luoghi',[])
                    ->with('autori',[])
                    ->with('eta',[])
                    ;*/
        }
    }
    
    
    ////AJAX
    public function ajaxDatiFiltro(){
        dump("contoller ricevuto ajax");
        error_log('contoller ricevuto ajax');
        session_start();
        $dl = new DataLyer();
        
        if (!isset($_SESSION['categorie'])){
                $_SESSION['categorie'] = $dl->getCategorie();
            }
            
            if (!isset($_SESSION['tipologie'])){
                $_SESSION['tipologie'] = $dl->getTipologie();
            }
            
            if (!isset($_SESSION['date'])){
                $_SESSION['date'] = $dl->getDate();
            }
            
            if (!isset($_SESSION['secoli'])){
                $_SESSION['secoli'] = $dl->getSecoli();
            }
           
            if (!isset($_SESSION['luoghi'])){
                $_SESSION['luoghi'] = $dl->getLuoghi();
            }
            
            if (!isset($_SESSION['autori'])){
                $_SESSION['autori'] = $dl->getAutori();
            }
            
            if (!isset($_SESSION['eta'])){
                $_SESSION['eta'] = $dl->getEta();
            }
        
       
        $response = array('output'=>true);
        return response()->json($response); 
        
    }
    
    
    //arrivo pagina creazione percorsi dopo filtro (POST)
    public function percorsiFiltro(Request $request) {
        
        session_start(); //fa partire la sessione e rimanda alla view index
        $dl = new DataLayer();
        
        //controlla che sia loggato
        if(isset($_SESSION['logged'])) {
            
        } else {
            
            $raggruppamento = $request->input('raggruppamento');
            
            $categoria_selezionata = null;
            $tipologia_selezionata = null;
            $data_selezionata = null;
            $secolo_selezionato = null;
            $luogo_selezionato = null;
            $autore_selezionato = null;
            $eta_selezionata = null;
            $sesso_selezionato = null;
            
           
            if ($raggruppamento == "Tipologia"){
                $tipologia_selezionata = $request->input('valore_raggruppamento_tipologia');
            }
            else if($raggruppamento == "Data di creazione"){
                $data_selezionata = $request->input('valore_raggruppamento_data');
            }
            else if($raggruppamento == "Secolo"){
                $secolo_selezionato = $request->input('valore_raggruppamento_secolo');
            }
            else if($raggruppamento == "Luogo di provenienza"){
                $luogo_selezionato = $request->input('valore_raggruppamento_luogo');
            }
            else if($raggruppamento == "Autore"){
                $autore_selezionato = $request->input('valore_raggruppamento_autore');
            }
            else if($raggruppamento == "Età visitatori"){
               $eta_selezionata = $request->input('valore_raggruppamento_eta'); 
            }
            else if($raggruppamento == "Categoria visitatori"){
                $categoria_selezionata = $request->input('valore_raggruppamento_categoria');
            }
            else if($raggruppamento == "Sesso visitatori"){
                $sesso_selezionato = $request->input('valore_raggruppamento_sesso');
            }
            
 
            
            
            
            $opere_selezionate = $request->input('opere_selezionate');
            $opere_selezionate_array = json_decode(stripslashes($opere_selezionate),true);
            //dump($raggruppamento);
            //dump($valore_categoria);
            //dump($opere_selezionate_array);
            //dump(gettype($opere_selezionate_array));
            
            
       
            $opere_selezionate_array_id = $dl->getIdSelezionate($opere_selezionate_array);
            dump($opere_selezionate_array_id);
            
            
            
            
            $categorie = $_SESSION['categorie'];
            $tipologie = $_SESSION['tipologie'];
            $date = $_SESSION['date'];
            $secoli = $_SESSION['secoli'];
            $luoghi = $_SESSION['luoghi'];
            $autori = $_SESSION['autori'];
            $eta = $_SESSION['eta'];
            
            $opere = $dl->getOpereMenoSelezionate($opere_selezionate_array_id);
            dump($opere);
            
            $opere_selezionate = $dl->getOpereSelezionate($opere_selezionate_array_id);
            dump($opere_selezionate);
                  
            
            $raggruppamenti = ["Qualsiasi", "Tipologia", "Data di creazione", "Secolo", "Luogo di provenienza", "Autore", "Visite totali",
                "Tempo totale delle visite", "Visite nell'ultimo anno", "Tempo totale delle visite dell'ultimo anno",
                "Meno visitate", "Età visitatori", "Categoria visitatori", "Sesso visitatori"];
           
            
            return view('filtro_percorsi')->with('logged',false)->with('opere',$opere)
                    ->with('opere_selezionate',$opere_selezionate)
                    ->with('raggruppamento',$raggruppamento)
                    ->with('raggruppamenti',$raggruppamenti)
                    ->with('categorie',$categorie)
                    ->with('tipologie',$tipologie)
                    ->with('date',$date)
                    ->with('secoli',$secoli)
                    ->with('luoghi',$luoghi)
                    ->with('autori',$autori)
                    ->with('eta',$eta)
                    ->with('categoria_selezionata',$categoria_selezionata)
                    ->with('tipologia_selezionata',$tipologia_selezionata)
                    ->with('data_selezionata',$data_selezionata)
                    ->with('secolo_selezionato',$secolo_selezionato)
                    ->with('luogo_selezionato',$luogo_selezionato)
                    ->with('autore_selezionato',$autore_selezionato)
                    ->with('eta_selezionata',$eta_selezionata)
                    ->with('sesso_selezionato',$sesso_selezionato)            
                    ;
            
        }
    }
}
