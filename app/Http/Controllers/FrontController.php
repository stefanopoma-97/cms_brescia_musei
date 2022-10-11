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
    
    public function confermaCreazionePercorso(){
        session_start();
        //controlla che sia loggato
        if(isset($_SESSION['logged'])) {
            
        } else {
            
            
            return view('conferma_creazione')->with('logged',false);
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
            //dump($raggruppamento);
            //dump($valore);
            //dump($opere_selezionate_array);
            //dump(gettype($opere_selezionate_array));
            
            
            
            $opere_selezionate_array_id = $dl->getIdSelezionate($opere_selezionate_array);
            //dump($opere_selezionate_array_id);
            
            
            
            $opere = $dl->getOpereMenoSelezionate($opere_selezionate_array_id);
            //dump($opere);
            
            $opere_selezionate = $dl->getOpereSelezionate($opere_selezionate_array_id);
            //dump($opere_selezionate);
                  
            
            
            
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
                //dump("prendo categorie");
                $categorie = $dl->getCategorie();
                $_SESSION['categorie'] = $categorie;
            }
            
            if (isset($_SESSION['tipologie'])){
                $tipologie = $_SESSION['tipologie'];
            }
            else {
                //dump("prendo tipologie");
                $tipologie = $dl->getTipologie();
                $_SESSION['tipologie'] = $tipologie;
            }
            
            if (isset($_SESSION['date'])){
                $date = $_SESSION['date'];
            }
            else {
                //dump("prendo date");
                $date = $dl->getDate();
                $_SESSION['date'] = $date;
            }
            
            if (isset($_SESSION['secoli'])){
                $secoli = $_SESSION['secoli'];
            }
            else {
                //dump("prendo secoli");
                $secoli = $dl->getSecoli();
                $_SESSION['secoli'] = $secoli;
            }
            
            if (isset($_SESSION['luoghi'])){
                $luoghi = $_SESSION['luoghi'];
            }
            else {
                //dump("prendo luoghi");
                $luoghi = $dl->getLuoghi();
                $_SESSION['luoghi'] = $luoghi;
            }
            
            if (isset($_SESSION['autori'])){
                $autori = $_SESSION['autori'];
                //dump($autori);
            }
            else {
                //dump("prendo autori");
                $autori = $dl->getAutori();
                $_SESSION['autori'] = $autori;
            }
            
            if (isset($_SESSION['eta'])){
                $eta = $_SESSION['eta'];
            }
            else {
                //dump("prendo eta");
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
        //dump("contoller ricevuto ajax");
        error_log('contoller ricevuto ajax');
        session_start();
        $dl = new DataLyer();
        
        /*
        $dl->getCategorie();
        $dl->getTipologie();
        $dl->getDate();
        $dl->getSecoli();
        $dl->getLuoghi();*/
        if (!isset($_SESSION['categorie'])){
            //dump("prendo categorie");
            $_SESSION['categorie'] = $dl->getCategorie();
        }
            
        if (!isset($_SESSION['tipologie'])){
            //dump("prendo tipologie");
            $_SESSION['tipologie'] = $dl->getTipologie();
        }
            
        if (!isset($_SESSION['date'])){
            //dump("prendo date");
            $_SESSION['date'] = $dl->getDate();
        }
            
        if (!isset($_SESSION['secoli'])){
            //dump("prendo secoli");
            $_SESSION['secoli'] = $dl->getSecoli();
        }
           
        if (!isset($_SESSION['luoghi'])){
            //dump("prendo luoghi");
            $_SESSION['luoghi'] = $dl->getLuoghi();
        }
        
        if (!isset($_SESSION['autori'])){
            //dump("prendo autori");
            $_SESSION['autori'] = $dl->getAutori();
            //dump($_SESSION['autori']);
        }

        if (!isset($_SESSION['eta'])){
            //dump("prendo eta");
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
            
            //estraggo array di opere selezionate
            $opere_selezionate = $request->input('opere_selezionate');
            $opere_selezionate_array = json_decode(stripslashes($opere_selezionate),true);
            $opere_selezionate_array_id = $dl->getIdSelezionate($opere_selezionate_array);
            
            $opere = null;
           
            if ($raggruppamento == "Tipologia"){
                $tipologia_selezionata = $request->input('valore_raggruppamento_tipologia');
                $opere = $dl->getOpereTipolgia($opere_selezionate_array_id, $tipologia_selezionata);
            }
            else if($raggruppamento == "Qualsiasi"){
                $opere = $dl->getOpereMenoSelezionate($opere_selezionate_array_id);
            }
            else if($raggruppamento == "Data di creazione"){
                $data_selezionata = $request->input('valore_raggruppamento_data');
                $opere = $dl->getOpereData($opere_selezionate_array_id, $data_selezionata);
            }
            else if($raggruppamento == "Secolo"){
                $secolo_selezionato = $request->input('valore_raggruppamento_secolo');
                $opere = $dl->getOpereSecolo($opere_selezionate_array_id, $secolo_selezionato);
            }
            else if($raggruppamento == "Luogo di provenienza"){
                $luogo_selezionato = $request->input('valore_raggruppamento_luogo');
                $opere = $dl->getOpereProvenienza($opere_selezionate_array_id, $luogo_selezionato);
            }
            else if($raggruppamento == "Autore"){
                $autore_selezionato = $request->input('valore_raggruppamento_autore');
                $nome_autore = $dl->getAutoreById($autore_selezionato);
                $opere = $dl->getOpereAutore($opere_selezionate_array_id, $nome_autore);
            }
            else if($raggruppamento == "Visite totali"){
                $opere = $dl->getOperePerVisite($opere_selezionate_array_id);
            }
            else if($raggruppamento == "Meno visitate"){
                $opere = $dl->getOperePerMenoVisite($opere_selezionate_array_id);
            }
            else if($raggruppamento == "Tempo totale delle visite"){
                $opere = $dl->getOperePerTempoVisite($opere_selezionate_array_id);
            }
            else if($raggruppamento == "Visite nell'ultimo anno"){
                $opere = $dl->getOperePerVisiteUltimoAnno($opere_selezionate_array_id);
            }
            else if($raggruppamento == "Tempo totale delle visite dell'ultimo anno"){
                $opere = $dl->getOperePerTempoVisiteUltimoAnno($opere_selezionate_array_id);
            }
            else if($raggruppamento == "Età visitatori"){
               $eta_selezionata = $request->input('valore_raggruppamento_eta'); 
               $opere = $dl->getOpereEta($opere_selezionate_array_id, $eta_selezionata);
            }
            else if($raggruppamento == "Categoria visitatori"){
                $categoria_selezionata = $request->input('valore_raggruppamento_categoria');
                $opere = $dl->getOpereCategoria($opere_selezionate_array_id, $categoria_selezionata);
            }
            else if($raggruppamento == "Sesso visitatori"){
                $sesso_selezionato = $request->input('valore_raggruppamento_sesso');
                $opere = $dl->getOpereSesso($opere_selezionate_array_id, $sesso_selezionato);
            }
            
           
 
            
            
            
            //dump($raggruppamento);
            //dump($valore_categoria);
            //dump($opere_selezionate_array);
            //dump(gettype($opere_selezionate_array));
            //dump(gettype($opere_selezionate_array[0]->id));
            
            
            //da array di opere selezionate estraggo array di ID
            
            //dump("array id opere selezionate: "+$opere_selezionate_array_id);
            
            //prendo opere, scartando quelle dell'array di ID
            
            //dump("Opere");
            //dump($opere);
            
            //prendo opere selezionate da array di ID
            $opere_selezionate = $dl->getOpereSelezionate($opere_selezionate_array_id);
            //dump("Opere selezionate");
            //dump($opere_selezionate);
            
            
            
            
            $categorie = $_SESSION['categorie'];
            $tipologie = $_SESSION['tipologie'];
            $date = $_SESSION['date'];
            $secoli = $_SESSION['secoli'];
            $luoghi = $_SESSION['luoghi'];
            $autori = $_SESSION['autori'];
            $eta = $_SESSION['eta'];
            
            
                  
            
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
    
    //arrivo pagina creazione percorsi prima volta (GET)
    public function confermaPercorso(Request $request) {
        
        
        session_start(); //fa partire la sessione e rimanda alla view index
        $dl = new DataLayer();
        
        
        if(isset($_SESSION['logged'])) {
            
        } else {
            
            $opere_id = $request->input('opere');
            //dump("Opere id recuperate");
            //dump($opere_id);
           
            $opere = $dl->getOpereByMultipleId($opere_id);
            //dump($opere);
                         
            return view('conferma_percorso')->with('logged',false)->with('opere',$opere)
                    ->with('valore',"pollo")
                    ;
            
            
        }
    }
    
    public function creaPercorso(Request $request) {
        
        
        session_start(); //fa partire la sessione e rimanda alla view index
        $dl = new DataLayer();
        
        
        if(isset($_SESSION['logged'])) {
            
        } else {
            
            $opere = $request->input('opere');
            $opere_array = json_decode(stripslashes($opere),true);
            //dump("Opere:");
            //dump($opere_array);
            $titolo = $request->input('titolo');
            //dump($titolo);
            $descrizione = $request->input('descrizione');
            //dump($descrizione);
            
            $int_array = [];
            foreach ($opere_array as $opera) {
               array_push($int_array, (int)($opera['id'])); 
            }
            //dump($int_array);
            
            $dl->creaPercorso($titolo, $descrizione, $opere_array);
            return redirect()->route('conferma_creazione');
            //return view('home');
            
            
        }
    }
    
    
    public function ajax() 
    {
        
        session_start();
        $client = ClientBuilder::create()
        ->withDriver('bolt', 'bolt://neo4j:neo4j_cms_brescia@localhost') // creates a bolt driver
        ->withDefaultDriver('bolt')
        ->build();
        
        
        
        //$dl = new DataLyer();
        
        
        if (!isset($_SESSION['categorie'])){
            $categorie = ($client->run('MATCH (c:Categoria)
            WITH distinct c.nome_categoria as nome, c.id as id
            RETURN id, nome'));
            $_SESSION['categorie'] = $categorie;
        }
            
        if (!isset($_SESSION['tipologie'])){
            //dump("prendo tipologie");
            $tipologie = ($client->run('MATCH (o:Opera)
            WITH distinct o.tipologia as tipologia
            RETURN tipologia'));
            $_SESSION['tipologie'] = $tipologie;
        }
            
        if (!isset($_SESSION['date'])){
            $date = ($client->run('MATCH (o:Opera)
            WITH distinct o.anno as anno
            RETURN anno'));
            $_SESSION['date'] = $date;
        }
            
        if (!isset($_SESSION['secoli'])){
            $secoli = ($client->run('MATCH (o:Opera)
            WITH distinct o.secolo as secolo
            WHERE secolo IS NOT NULL
            RETURN secolo'));
            $_SESSION['secoli'] = $secoli;
        }
           
        if (!isset($_SESSION['luoghi'])){
            $luoghi = ($client->run('MATCH (o:Opera)
            WITH distinct o.provenienza as luogo
            WHERE luogo IS NOT null
            RETURN luogo'));
            $_SESSION['luoghi'] = $luoghi;
        }
        
        if (!isset($_SESSION['autori'])){
            $autori = ($client->run('MATCH (a:Autore)<-[n:CREATA]-(:Opera)
            WITH a.nome as nome, a.id as id, count(n) as numero_creazioni
            RETURN id, nome'));
            $_SESSION['autori'] = $autori;
        }

        if (!isset($_SESSION['eta'])){
            $eta = ($client->run('MATCH (v:Visitatore)
            WITH distinct v.eta as eta
            WHERE eta IS NOT null
            RETURN eta
            ORDER BY eta'));
            $_SESSION['eta'] = $eta;
            
        }
        
        

        return response()->json(['success'=>'Laravel ajax example is being processed.']);
    }
}
