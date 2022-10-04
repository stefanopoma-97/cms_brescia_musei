<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\LibUser;
use App\Sentiero;
use App\DatiSentiero;
use App\Citta;
use App\Preferiti;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Laudis\Neo4j\Authentication\Authenticate;
use Laudis\Neo4j\ClientBuilder;
use Laudis\Neo4j\Contracts\TransactionInterface;


//AUTENTICAZIONE
class DataLayer extends Model
{
   
    private $client;

    /*
     * Nel costruttore viene creata la connessione con il database
     */
    public function __construct()
  {
    $builder = ClientBuilder::create();
    // A client manages the drivers as configured by the builder.
    $this->client = $builder
        ->withDriver('bolt', 'bolt://neo4j:neo4j_cms_brescia@localhost') // creates a bolt driver
        ->withDefaultDriver('bolt')
        ->build();
    
    
  }
  

    /*
     * Vengono restituite tutte le opere ordinate per id
     * la query esplicita quali proprietà ritornare per ogni nodo
     * le proprietà sono le seguenti: id, titolo, tipologia,autore, anno, secolo, luogo, visite, tempo, per_categoria, per_eta, per_sesso
     * 
     * Output: array di CypherMap
     */
    public function getOpere() {

        $results = ($this->client->run('MATCH (o:Opera)
            WITH o.id as id, o.titolo as titolo, o.autore as autore, o.tipologia as tipologia, o.anno as anno, o.secolo as secolo, o.provenienza as luogo, null as visite, null as tempo, null as per_categoria, null as per_eta, null as per_sesso 
            RETURN id, titolo, tipologia,autore, anno, secolo, luogo, visite, tempo, per_categoria, per_eta, per_sesso
            ORDER BY id ASC'));

        return $results;
    }
    
    /*
     * Ritorna tutte le opere il cui ID è contenuto in un array
     * Senza il cast a int di tutti i valori dell'array la query potrebbe non funzionare correttamente
     * 
     * Output: array di CypherMap
     */
    public function getOpereByMultipleId($array) {
        
        $int_array = [];
        foreach ($array as $opera) {
           array_push($int_array, (int)($opera['id'])); 
         }
        
        
        $results = ($this->client->run('MATCH (o:Opera)
            WITH o.id as id, o.titolo as titolo, o.autore as autore, o.tipologia as tipologia, o.anno as anno, o.secolo as secolo, o.provenienza as luogo, null as visite, null as tempo, null as per_categoria, null as per_eta, null as per_sesso 
            WHERE id IN $int_array
            RETURN id, titolo, tipologia,autore, anno, secolo, luogo, visite, tempo, per_categoria, per_eta, per_sesso
            ORDER BY id ASC',['int_array' => $int_array]));
        
        $opere = array();
        foreach ($results as $value) {
           array_push($opere, $value);
        }
         
        return $opere;
    }
    
    /*
     * Dato un titolo, una descrizione e un array di ID opere, viene creato un percorso
     * prima viene creato un id casuale
     * successivamente viene creato il nodo percorso
     * infine viene creata una relazione tra il nodo e ogni id del array di opere
     * 
     * Output: bool
     */
    public function creaPercorso($titolo, $descrizione, $opere){
        $id = (string)uniqid(rand());
        $int_array = [];
        foreach ($opere as $opera) {
           array_push($int_array, (int)($opera['id'])); 
        }
         
        //creo percorso con dato id
        ($this->client->run('MERGE (p:Percorso {titolo:$titolo,descrizione:$descrizione, id:$id})',['descrizione' => $descrizione, 'titolo'=>$titolo, 'id'=>$id]));
        
        //creo relazioni
        ($this->client->run('MATCH (p:Percorso)
            MATCH (o:Opera)
            WHERE o.id in $int_array AND p.id = $id
            MERGE (p)-[:PERCORSO_OPERA]->(o)',['int_array' => $int_array, 'id'=>$id]));
        
        return true;
    }
    
    /*
     * Ritorna tutte le opere ordinate per ID
     * all'insieme di tutte le opere vengono sottratte le opere il cui ID è contenuto in $array_id
     * Questa funzione è utile per filtrare tutte le opere quando però un certo sottoinsieme è già stato selezionato
     * 
     * Output: array di CypherMap
     */
    public function getOpereMenoSelezionate($array_id) {
        
        $results = ($this->client->run('MATCH (o:Opera)
            WITH o.id as id, o.titolo as titolo, o.autore as autore, o.tipologia as tipologia, o.anno as anno, o.secolo as secolo, o.provenienza as luogo, null as visite, null as tempo, null as per_categoria, null as per_eta, null as per_sesso 
            RETURN id, titolo, tipologia,autore, anno, secolo, luogo, visite, tempo, per_categoria, per_eta, per_sesso
            ORDER BY id ASC'));
       
        $opere = array();
        foreach ($results as $value) {
           if(!(in_array($value->get('id'), $array_id)))
               array_push($opere, $value);
         }
        
        return $opere;
    }
    
    /*
     * Vengono restituite tutte le opere facenti parte di una data tipologia (stringa)
     * le opere sono ordinate per id
     * Al elenco ricavato vengono sottratte le opere il cui ID fa parte del array $array_id
     * 
     * Output: array di CypherMap
     */
    public function getOpereTipolgia($array_id, $tipologia) {
        
        $results = ($this->client->run('MATCH (o:Opera)
            WITH o.id as id, o.titolo as titolo, o.autore as autore, o.tipologia as tipologia, o.anno as anno, o.secolo as secolo, o.provenienza as luogo, null as visite, null as tempo, null as per_categoria, null as per_eta, null as per_sesso
            WHERE tipologia=$tipologia
            RETURN id, titolo, tipologia,autore, anno, secolo, luogo, visite, tempo, per_categoria, per_eta, per_sesso
            ORDER BY id ASC', ['tipologia' => $tipologia]));
       
        $opere = array();
        foreach ($results as $value) {
           if(!(in_array($value->get('id'), $array_id))){
              array_push($opere, $value); 
           }
               
         }
        
        return $opere;
    }
    
    /*
     * Vengono restituite tutte le opere create in un certo anno (int)
     * Vengono sottratte le opere il cui id è contenuto in $array_id
     * 
     * Output: array di CypherMap
     */
    public function getOpereData($array_id, $data) {
        
        $int_data =(int) $data;
        
        $results = ($this->client->run('MATCH (o:Opera)
            WITH o.id as id, o.titolo as titolo, o.autore as autore, o.tipologia as tipologia, o.anno as anno, o.secolo as secolo, o.provenienza as luogo, null as visite, null as tempo, null as per_categoria, null as per_eta, null as per_sesso
            WHERE anno=$int_data
            RETURN id, titolo, tipologia,autore, anno, secolo, luogo, visite, tempo, per_categoria, per_eta, per_sesso
            ORDER BY id ASC', ['int_data' => $int_data]));
       
        $opere = array();
        foreach ($results as $value) {
           if(!(in_array($value->get('id'), $array_id))){
               array_push($opere, $value);
           }
               
         }
        
        return $opere;
    }
    
    /*
     * Vengono restituite le opere create in un certo secolo (int)
     * Vengono sottratte le opere il cui id è contenuto in $array_id
     * 
     * Output: array di CypherMap
     */
    public function getOpereSecolo($array_id, $secolo) {
        $int_secolo =(int) $secolo;
        $results = ($this->client->run('MATCH (o:Opera)
            WITH o.id as id, o.titolo as titolo, o.autore as autore, o.tipologia as tipologia, o.anno as anno, o.secolo as secolo, o.provenienza as luogo, null as visite, null as tempo, null as per_categoria, null as per_eta, null as per_sesso
            WHERE secolo=$int_secolo
            RETURN id, titolo, tipologia,autore, anno, secolo, luogo, visite, tempo, per_categoria, per_eta, per_sesso
            ORDER BY id ASC', ['int_secolo' => $int_secolo]));
       
        $opere = array();
        foreach ($results as $value) {
           if(!(in_array($value->get('id'), $array_id))){
               array_push($opere, $value);
           }
               
         }
        
        return $opere;
    }
    
    /*
     * Vengono restituite tutte le opere create in un certo luogo (stringa)
     * Vengono sottratte le opere il cui id è contenuto in $array_id
     * 
     * Output: array di CypherMap
     */
    public function getOpereProvenienza($array_id, $provenienza) {
        
        $results = ($this->client->run('MATCH (o:Opera)
            WITH o.id as id, o.titolo as titolo, o.autore as autore, o.tipologia as tipologia, o.anno as anno, o.secolo as secolo, o.provenienza as luogo, null as visite, null as tempo, null as per_categoria, null as per_eta, null as per_sesso
            WHERE luogo=$provenienza
            RETURN id, titolo, tipologia,autore, anno, secolo, luogo, visite, tempo, per_categoria, per_eta, per_sesso
            ORDER BY id ASC', ['provenienza' => $provenienza]));
       
        $opere = array();
        foreach ($results as $value) {
           if(!(in_array($value->get('id'), $array_id))){
               array_push($opere, $value);
           }
               
         }
        
        return $opere;
    }
    
    /*
     * Vengono restituite tutte le opere con almeno una vistita
     * le opere sono ordinate per numero di visite
     * Vengono sottratte le opere il cui id è contenuto in $array_id
     * 
     * Output: array di CypherMap
     */
    public function getOperePerVisite($array_id) {
        
        $results = ($this->client->run('MATCH (v:Visita)-[:VISITA_OPERA]->(o:Opera) 
            WITH o.id as id, o.titolo as titolo, o.autore as autore, o.tipologia as tipologia, o.anno as anno, o.secolo as secolo, o.provenienza as luogo, null as tempo, null as per_categoria, null as per_eta, null as per_sesso, count(v) as visite
            RETURN id, titolo, tipologia,autore, anno, secolo, luogo, visite, tempo, per_categoria, per_eta, per_sesso
            ORDER BY visite DESC'));
       
        $opere = array();
        foreach ($results as $value) {
           if(!(in_array($value->get('id'), $array_id))){
               array_push($opere, $value);
           }
               
         }
        
        return $opere;
    }
    
    /*
     * Vengono restituite tutte le opere con almeno una visita nell'ultimo anno
     * Le opere sono ordinate per numero di visite
     * Vengono sottratte le opere il cui id è contenuto in $array_id
     * 
     * Output: array di CypherMap
     */
     public function getOperePerVisiteUltimoAnno($array_id) {
        
        $results = ($this->client->run('MATCH (v:Visita)-[:VISITA_OPERA]->(o:Opera) 
            WITH o.id as id, o.titolo as titolo, o.autore as autore, o.tipologia as tipologia, o.anno as anno, o.secolo as secolo, o.provenienza as luogo, null as tempo, null as per_categoria, null as per_eta, null as per_sesso, count(v) as visite, v.data_visita as data_visita
            WHERE visite>0 AND data_visita.year = date().year
            RETURN id, titolo, tipologia,autore, anno, secolo, luogo, visite, tempo, per_categoria, per_eta, per_sesso
            ORDER BY visite DESC'));
       
        $opere = array();
        foreach ($results as $value) {
           if(!(in_array($value->get('id'), $array_id))){
               array_push($opere, $value);
           }
               
         }
        
        return $opere;
    }
    
    /*
     * Vengono ritornare tutte le opere con almeno una visita
     * Le opere vengono ordinate per numero visite (dal minore al maggiore)
     * Vengono sottratte le opere il cui id è contenuto in $array_id
     * 
     * Output: array di CypherMap
     */
    public function getOperePerMenoVisite($array_id) {
        
        $results = ($this->client->run('MATCH (v:Visita)-[:VISITA_OPERA]->(o:Opera) 
            WITH o.id as id, o.titolo as titolo, o.autore as autore, o.tipologia as tipologia, o.anno as anno, o.secolo as secolo, o.provenienza as luogo, null as tempo, null as per_categoria, null as per_eta, null as per_sesso, count(v) as visite
            RETURN id, titolo, tipologia,autore, anno, secolo, luogo, visite, tempo, per_categoria, per_eta, per_sesso
            ORDER BY visite ASC'));
       
        $opere = array();
        foreach ($results as $value) {
           if(!(in_array($value->get('id'), $array_id))){
               array_push($opere, $value);
           }
               
         }
        
        return $opere;
    }
    
    /*
     * Vengono selezionate le opere con almeno una visita
     * Le opere vengono ordinate per durata complessiva delle visite (espressa in secondi)
     * Vengono sottratte le opere il cui id è contenuto in $array_id
     * 
     * Output: array di CypherMap
     */
    public function getOperePerTempoVisite($array_id) {
        
        $results = ($this->client->run('MATCH (v:Visita)-[:VISITA_OPERA]->(o:Opera) 
            WITH o.id as id, o.titolo as titolo, o.autore as autore, o.tipologia as tipologia, o.anno as anno, o.secolo as secolo, o.provenienza as luogo, null as per_categoria, null as per_eta, null as per_sesso, count(v) as visite, sum(v.durata) as tempo
            RETURN id, titolo, tipologia,autore, anno, secolo, luogo, visite, tempo, per_categoria, per_eta, per_sesso
            ORDER BY visite DESC'));
       
        $opere = array();
        foreach ($results as $value) {
           if(!(in_array($value->get('id'), $array_id))){
               array_push($opere, $value);
           }
               
         }
        
        return $opere;
    }
    
    /*
     * Vengono selezioate con almeno una visita effettuata nell'ultimo anno
     * le opere sono ordinate per durata delle visite (in secondi)
     * Vengono sottratte le opere il cui id è contenuto in $array_id
     * 
     * Output: array di CypherMap
     */
    public function getOperePerTempoVisiteUltimoAnno($array_id) {
        
        $results = ($this->client->run('MATCH (v:Visita)-[:VISITA_OPERA]->(o:Opera) 
            WITH o.id as id, o.titolo as titolo, o.autore as autore, o.tipologia as tipologia, o.anno as anno, o.secolo as secolo, o.provenienza as luogo, null as per_categoria, null as per_eta, null as per_sesso, count(v) as visite, sum(v.durata) as tempo,  v.data_visita as data_visita
            WHERE visite>0 AND data_visita.year = date().year
            RETURN id, titolo, tipologia,autore, anno, secolo, luogo, visite, tempo, per_categoria, per_eta, per_sesso
            ORDER BY visite DESC'));
       
        $opere = array();
        foreach ($results as $value) {
           if(!(in_array($value->get('id'), $array_id))){
               array_push($opere, $value);
           }
               
         }
        
        return $opere;
    }
    
    /*
     * Dato un id viene restituito un autore
     * Le proprietà selezionate dell'autore sono solamente il nome (stringa)
     * Vengono sottratte le opere il cui id è contenuto in $array_id
     * 
     * Output: stringa
     */
    public function getAutoreById($id) {
        
        $int_id = (int)$id;
        
        $results = ($this->client->run('MATCH (o:Autore)
            WITH o.id as id, o.nome as nome
            WHERE id=$int_id
            RETURN nome
            ', ['int_id' => $int_id]));
         return $results[0]->get('nome');
    }
    
    /*
     * Vengono restituite le opere di un certo autore
     * Vengono sottratte le opere il cui id è contenuto in $array_id
     * 
     * Output: array di CypherMap
     */
    public function getOpereAutore($array_id, $autore) {
        
        $results = ($this->client->run('MATCH (o:Opera)
            WITH o.id as id, o.titolo as titolo, o.autore as autore, o.tipologia as tipologia, o.anno as anno, o.secolo as secolo, o.provenienza as luogo, null as visite, null as tempo, null as per_categoria, null as per_eta, null as per_sesso
            WHERE autore=$autore
            RETURN id, titolo, tipologia,autore, anno, secolo, luogo, visite, tempo, per_categoria, per_eta, per_sesso
            ORDER BY id ASC', ['autore' => $autore]));
       
        $opere = array();
        foreach ($results as $value) {
           if(!(in_array($value->get('id'), $array_id))){
               array_push($opere, $value);
           }
               
         }
        
        return $opere;
    }
    
    /*
     * Data una certa età (int)
     * per ogni opera viene calcolata la percentuale di visite associata a visitatori di quella età
     * Le opere vengono ordinate per il valore percentuale ricavato
     * Vengono sottratte le opere il cui id è contenuto in $array_id
     * 
     * Output: array di CypherMap
     */
    public function getOpereEta($array_id, $eta) {
        
        $int_eta = (int)$eta;
        $results = ($this->client->run('MATCH (o:Opera)<-[:VISITA_OPERA]-(v:Visita) -[:VISITA_VISITATORE]->(vi:Visitatore)
            WITH o as opera,o.id as id, o.titolo as titolo, o.autore as autore, o.tipologia as tipologia, o.anno as anno, o.secolo as secolo, o.provenienza as luogo, null as tempo, null as per_categoria, null as per_eta, null as per_sesso, count(v) as visite
            MATCH (o:Opera)<-[:VISITA_OPERA]-(v:Visita) -[:VISITA_VISITATORE]->(vi:Visitatore)
            WITH count(v) as numero_visite2, vi.eta as eta, o.titolo as titolo2, visite as numero_visite_tot, titolo as titolo, id as id,autore as autore, tipologia as tipologia, anno as anno, secolo as secolo, luogo as luogo, tempo as tempo, per_categoria as per_categoria, per_sesso as per_sesso
            WHERE titolo2 = titolo AND  eta = $int_eta
            RETURN id, titolo, tipologia,autore, anno, secolo, luogo, numero_visite2 as visite, tempo, per_categoria, per_sesso,numero_visite_tot, numero_visite2, numero_visite2*100/numero_visite_tot as per_eta, eta
            ORDER BY id ASC, per_eta DESC', ['int_eta' => $int_eta]));
       
        $opere = array();
        foreach ($results as $value) {
           if(!(in_array($value->get('id'), $array_id))){
               array_push($opere, $value);
           }
               
         }
        
        return $opere;
    }
    
    /*
     * Data una certa categoria (stringa)
     * per ogni opera viene calcolata la percentuale di visite associata a visitatori di quella categoria
     * Le opere vengono ordinate per il valore percentuale ricavato
     * Vengono sottratte le opere il cui id è contenuto in $array_id
     * 
     * Output: array di CypherMap
     */
    public function getOpereCategoria($array_id, $categoria) {
        
        $int_categoria = (int)$categoria;
        $results = ($this->client->run('MATCH (o:Opera)<-[:VISITA_OPERA]-(v:Visita) -[:VISITA_VISITATORE]->(:Visitatore)-[:CATEGORIA]->(c:Categoria)
            WITH o as opera,o.id as id, o.titolo as titolo, o.autore as autore, o.tipologia as tipologia, o.anno as anno, o.secolo as secolo, o.provenienza as luogo, null as tempo, null as per_categoria, null as per_eta, null as per_sesso, count(v) as numero_visite
            MATCH (o:Opera)<-[:VISITA_OPERA]-(v:Visita) -[:VISITA_VISITATORE]->(:Visitatore)-[:CATEGORIA]->(c:Categoria)
            WITH count(v) as numero_visite2, c.nome_categoria as nome_categoria, c.id as id_categoria, o.titolo as titolo2, numero_visite as numero_visite_tot, titolo as titolo, id as id,autore as autore, tipologia as tipologia, anno as anno, secolo as secolo, luogo as luogo, tempo as tempo, per_categoria as per_categoria, per_sesso as per_sesso, per_eta as per_eta
            WHERE titolo2 = titolo AND id_categoria=$int_categoria
            RETURN id, titolo, tipologia, autore, anno, secolo, luogo,numero_visite_tot, numero_visite2 as visite, numero_visite2*100/numero_visite_tot as per_categoria, nome_categoria, per_sesso, per_eta, tempo
            ORDER BY per_categoria DESC', ['int_categoria' => $int_categoria]));
       
        $opere = array();
        foreach ($results as $value) {
           if(!(in_array($value->get('id'), $array_id))){
               array_push($opere, $value);
           }
               
         }
        
        return $opere;
    }
    
    /*
     * Data una certo sesso (stringa)
     * per ogni opera viene calcolata la percentuale di visite associata a visitatori di quel sesso
     * Le opere vengono ordinate per il valore percentuale ricavato
     * Vengono sottratte le opere il cui id è contenuto in $array_id
     * 
     * Output: array di CypherMap
     */
    public function getOpereSesso($array_id, $sesso) {
        
        $results = ($this->client->run('MATCH (o:Opera)<-[:VISITA_OPERA]-(v:Visita) -[:VISITA_VISITATORE]->(vi:Visitatore)
            WITH o as opera,o.id as id, o.titolo as titolo, o.autore as autore, o.tipologia as tipologia, o.anno as anno, o.secolo as secolo, o.provenienza as luogo, null as tempo, null as per_categoria, null as per_eta, null as per_sesso, count(v) as visite
            MATCH (o:Opera)<-[:VISITA_OPERA]-(v:Visita) -[:VISITA_VISITATORE]->(vi:Visitatore)
            WITH count(v) as numero_visite2, vi.sesso as sesso, o.titolo as titolo2, visite as numero_visite_tot, titolo as titolo, id as id,autore as autore, tipologia as tipologia, anno as anno, secolo as secolo, luogo as luogo, tempo as tempo, per_categoria as per_categoria, per_sesso as per_sesso, per_eta as per_eta
            WHERE titolo2 = titolo AND  sesso = $sesso
            RETURN id, titolo, tipologia,autore, anno, secolo, luogo, numero_visite2 as visite, tempo, per_categoria, per_eta,numero_visite_tot, numero_visite2, numero_visite2*100/numero_visite_tot as per_sesso, sesso
            ORDER BY id ASC, per_sesso DESC', ['sesso' => $sesso]));
       
        $opere = array();
        foreach ($results as $value) {
           if(!(in_array($value->get('id'), $array_id))){
               array_push($opere, $value);
           }
               
         }
        
        return $opere;
    }
    
    /*
     * Vengono restituite le opere il cui ID è presente in $array_id
     * 
     * Output: array di CypherMap
     */
    public function getOpereSelezionate($array_id) {
        
        $results = ($this->client->run('
            MATCH (o:Opera)
            WITH o.id as id, o.titolo as titolo, o.autore as autore, o.tipologia as tipologia, o.anno as anno, o.secolo as secolo, o.provenienza as luogo, null as visite, null as tempo, null as per_categoria, null as per_eta, null as per_sesso 
            WHERE id IN $array_id
            RETURN id, titolo, tipologia,autore, anno, secolo, luogo, visite, tempo, per_categoria, per_eta, per_sesso
            ORDER BY id ASC', ['array_id' => $array_id]));
        
       
        $opere = array();
        foreach ($results as $value) {
           if((in_array($value->get('id'), $array_id)))
               array_push($opere, $value);
         }
        
        return $opere;
         
    }
    
    
    public function getOperaByIDOld($id) {
        $int_id = (int)$id; 
        $results = ($this->client->run('
        MATCH (o:Opera)
        WITH o.id as id, o.titolo as titolo, o.autore as autore, o.tipologia as tipologia, o.anno as anno, o.secolo as secolo, o.provenienza as luogo, null as visite, null as tempo, null as per_categoria, null as per_eta, null as per_sesso 
        MATCH (o:Opera)-[:CREATA]->(a:Autore)
        WITH a.nome as autore, id as id, titolo as titolo, tipologia as tipologia, anno as anno, secolo as secolo, luogo as luogo, visite as visite, tempo as tempo, per_categoria as per_categoria, per_eta as per_eta, per_sesso as per_sesso, o.id as id2
        MATCH (v:Visita)-[:VISITA_OPERA]->(o:Opera) 
        WITH count(v) as visite, sum(v.durata) as tempo, autore as autore, id as id, titolo as titolo, tipologia as tipologia, anno as anno, secolo as secolo, luogo as luogo, per_categoria as per_categoria, per_eta as per_eta, per_sesso as per_sesso, id2 as id2, o as o
        WHERE id2 = $int_id AND id = $int_id AND o.id = $int_id
        RETURN id, titolo, tipologia,autore, anno, secolo, luogo, visite, tempo, per_categoria, per_eta, per_sesso
        ', ['int_id' => $int_id]));
        
        return $results[0];
    }
    
    /*
     * Dato un id
     * Viene restituita l'opera con quel id
     * Viene poi restituita la stringa autore associata a quellea specifica opera (se presente)
     * Viene poi restituito il numero di visite e il tempo di visite dell'opera (se esistono visite)
     * 
     * Output: array di CypherMap e stringhe. In posizione 0 opera, in posizione 1 autore, in posizione 2 visite, in posizione 3 tempo
     */
    public function getOperaByID($id) {
        $int_id = (int)$id; 
        
        $results = ($this->client->run('
        MATCH (o:Opera)
        WITH o.id as id, o.titolo as titolo, o.autore as autore, o.tipologia as tipologia, o.anno as anno, o.secolo as secolo, o.provenienza as luogo, null as visite, null as tempo, null as per_categoria, null as per_eta, null as per_sesso 
        WHERE id = $int_id
        RETURN id, titolo, tipologia,autore, anno, secolo, luogo, visite, tempo, per_categoria, per_eta, per_sesso
        ', ['int_id' => $int_id]));
        $opera = $results[0];
        
        $results = ($this->client->run('
        MATCH (o:Opera)-[:CREATA]->(a:Autore)
        WITH a.nome as autore, o.id as id
        WHERE id = $int_id
        RETURN autore
        ', ['int_id' => $int_id]));
        
        $autore="";
        if (count($results)>0){
           $autore = $results[0]->get('autore');
        }
        
        
        $results = ($this->client->run('
        MATCH (v:Visita)-[:VISITA_OPERA]->(o:Opera) 
        WITH count(v) as visite, sum(v.durata) as tempo, o.id as id        
        WHERE id = $int_id
        RETURN visite, tempo
        ', ['int_id' => $int_id]));
        
        $visite = 0;
        $tempo = 0;
        if (count($results)>0){
           $visite = $results[0]->get('visite');
           $tempo = $results[0]->get('tempo');
        }
        
        
        return [$opera, $autore, $visite, $tempo];
    }
    
    /*
     * Dato un array di opere selezionate (conversione di un json in array php)
     * viene restituito l'array degli id
     * 
     * Output: array 
     */
    public function getIdSelezionate($array){
        $id = array();
        foreach ($array as $v) {
            array_push($id, $v['id']);
          }
        return $id;
    }
    
    /*
     * Ritorna tutte le categoria nel database
     *     
     * Output: array di CypherMap
     */
    public function getCategorie(){
        
        $results = ($this->client->run('MATCH (c:Categoria)
            WITH distinct c.nome_categoria as nome, c.id as id
            RETURN id, nome'));
        return $results;
    }
    
    /*
     * Ritorna tutte le tipologie nel database
     *     
     * Output: array di CypherMap
     */
    public function getTipologie(){
        
        $results = ($this->client->run('MATCH (o:Opera)
            WITH distinct o.tipologia as tipologia
            RETURN tipologia'));
        return $results;
    }
    
    /*
     * ritorna tutte le date nel database (data di creazione di un'opera)
     *     
     * Output: array di CypherMap 
     */
    public function getDate(){
        
        $results = ($this->client->run('MATCH (o:Opera)
            WITH distinct o.anno as anno
            RETURN anno'));
        return $results;
    }
    
    /*
     * Ritorna tutti i secoli presenti nel database (secoli in cui sono state create le opere)
     *      
     * Output: array di CypherMap
     */
    public function getSecoli(){
        
        $results = ($this->client->run('MATCH (o:Opera)
            WITH distinct o.secolo as secolo
            WHERE secolo IS NOT NULL
            RETURN secolo'));
        return $results;
    }
    
    /*
     * Ritorna i luoghi presenti nel database (luoghi di creazione delle opere)
     *      
     * Output: array di CypherMap
     */
    public function getLuoghi(){
        
        $results = ($this->client->run('MATCH (o:Opera)
            WITH distinct o.provenienza as luogo
            WHERE luogo IS NOT null
            RETURN luogo'));
        return $results;
    }
    
    /*
     * Ritorna tutti gli autori presenti nel database
     *      
     * Output: array di CypherMap
     */
    public function getAutori(){
        
        $results = ($this->client->run('MATCH (a:Autore)<-[n:CREATA]-(:Opera)
            WITH a.nome as nome, a.id as id, count(n) as numero_creazioni
            RETURN id, nome'));
        return $results;
    }
    
    /*
     * ritorna le età di tutti i visitatori (evitando duplicati)
     *       
     * Output: array di CypherMap
     */
    public function getEta(){
        
        $results = ($this->client->run('MATCH (v:Visitatore)
            WITH distinct v.eta as eta
            WHERE eta IS NOT null
            RETURN eta
            ORDER BY eta'));
        return $results;
    }
    
}
