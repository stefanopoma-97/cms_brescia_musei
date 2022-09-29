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

    public function __construct()
  {
    $builder = ClientBuilder::create();
    // A client manages the drivers as configured by the builder.
    $this->client = $builder
        ->withDriver('bolt', 'bolt://neo4j:neo4j_cms_brescia@localhost') // creates a bolt driver
        ->withDefaultDriver('bolt')
        ->build();
  }
  


    public function getOpere() {
        
        
        
        $results = ($this->client->run('MATCH (o:Opera)
            WITH o.id as id, o.titolo as titolo, o.autore as autore, o.tipologia as tipologia, o.anno as anno, o.secolo as secolo, o.provenienza as luogo, null as visite, null as tempo, null as per_categoria, null as per_eta, null as per_sesso 
            RETURN id, titolo, tipologia,autore, anno, secolo, luogo, visite, tempo, per_categoria, per_eta, per_sesso
            ORDER BY id ASC'));
        
        //->withDriver('https', 'https://test.com', Authenticate::basic('neo4j', 'neo4j_cms_brescia')) // creates an http driver
        //->withDriver('neo4j', 'neo4j://neo4j.test.com?database=my-database', Authenticate::oidc('token')) // creates an auto routed driver with an OpenID Connect token
        
        /*
        $opera1 = new Opera("1", "nome1", "autore1", "anno1", "100");
        $opera2 = new Opera("2","nome2", "autore2", "anno2", "2000");
        $opera3 = new Opera("3","nome3", "autore3", "anno3", "500");
        
       
        $opere = array($opera1, $opera2, $opera3);*/
        return $results;
    }
    
    public function getOpereMenoSelezionate($array_id) {
        
        $results = ($this->client->run('MATCH (o:Opera)
            WITH o.id as id, o.titolo as titolo, o.autore as autore, o.tipologia as tipologia, o.anno as anno, o.secolo as secolo, o.provenienza as luogo, null as visite, null as tempo, null as per_categoria, null as per_eta, null as per_sesso 
            RETURN id, titolo, tipologia,autore, anno, secolo, luogo, visite, tempo, per_categoria, per_eta, per_sesso
            ORDER BY id ASC'));
        /*
        $opera1 = new Opera("1", "nome1", "autore1", "anno1", "100");
        $opera2 = new Opera("2","nome2", "autore2", "anno2", "2000");
        $opera3 = new Opera("3","nome3", "autore3", "anno3", "500");
        
        $opere = array();
        $temp = array($opera1, $opera2, $opera3);
        foreach ($temp as $value) {
            if(!(in_array($value->id, $array_id)))
                array_push($opere, $value);
          }
         * */
        $opere = array();
        foreach ($results as $value) {
           if(!(in_array($value->get('id'), $array_id)))
               array_push($opere, $value);
         }
        
        return $opere;
    }
    
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
    
    public function getAutoreById($id) {
        
        $int_id = (int)$id;
        
        $results = ($this->client->run('MATCH (o:Autore)
            WITH o.id as id, o.nome as nome
            WHERE id=$int_id
            RETURN nome
            ', ['int_id' => $int_id]));
         return $results[0]->get('nome');
    }
    
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
    
    public function getOpereSelezionate($array_id) {
        
        $results = ($this->client->run('
            MATCH (o:Opera)
            WITH o.id as id, o.titolo as titolo, o.autore as autore, o.tipologia as tipologia, o.anno as anno, o.secolo as secolo, o.provenienza as luogo, null as visite, null as tempo, null as per_categoria, null as per_eta, null as per_sesso 
            WHERE id IN $array_id
            RETURN id, titolo, tipologia,autore, anno, secolo, luogo, visite, tempo, per_categoria, per_eta, per_sesso
            ORDER BY id ASC', ['array_id' => $array_id]));
        
        /*
        $opera1 = new Opera("1", "nome1", "autore1", "anno1", "100");
        $opera2 = new Opera("2","nome2", "autore2", "anno2", "2000");
        $opera3 = new Opera("3","nome3", "autore3", "anno3", "500");
        
       
        $opere = array();
        $temp = array($opera1, $opera2, $opera3);
        foreach ($temp as $value) {
            if((in_array($value->id, $array_id)))
                array_push($opere, $value);
          }
         * */
        
        $opere = array();
        foreach ($results as $value) {
           if((in_array($value->get('id'), $array_id)))
               array_push($opere, $value);
         }
        
        return $opere;
         
    }
    
    
    public function getOperaByID($id) {
        $opera1 = new Opera($id, "nome1", "autore1", "anno1", "100");
        return $opera1;
    }
    
    public function getIdSelezionate($array){
        $id = array();
        foreach ($array as $v) {
            array_push($id, $v['id']);
          }
        return $id;
    }
    
    //ritorna: id, nome
    public function getCategorie(){
        
        $results = ($this->client->run('MATCH (c:Categoria)
            WITH distinct c.nome_categoria as nome, c.id as id
            RETURN id, nome'));
        return $results;
    }
    
    //ritorno: tipologia
    public function getTipologie(){
        
        $results = ($this->client->run('MATCH (o:Opera)
            WITH distinct o.tipologia as tipologia
            RETURN tipologia'));
        return $results;
    }
    
    //ritorno: anno
    public function getDate(){
        
        $results = ($this->client->run('MATCH (o:Opera)
            WITH distinct o.anno as anno
            RETURN anno'));
        return $results;
    }
    
    //ritorno: secolo
    public function getSecoli(){
        
        $results = ($this->client->run('MATCH (o:Opera)
            WITH distinct o.secolo as secolo
            WHERE secolo IS NOT NULL
            RETURN secolo'));
        return $results;
    }
    
    //ritorno: luogo
    public function getLuoghi(){
        
        $results = ($this->client->run('MATCH (o:Opera)
            WITH distinct o.provenienza as luogo
            WHERE luogo IS NOT null
            RETURN luogo'));
        return $results;
    }
    
    //ritorno: id, nome
    public function getAutori(){
        
        $results = ($this->client->run('MATCH (a:Autore)<-[n:CREATA]-(:Opera)
            WITH a.nome as nome, a.id as id, count(n) as numero_creazioni
            RETURN id, nome'));
        return $results;
    }
    
    //ritorno: eta
    public function getEta(){
        
        $results = ($this->client->run('MATCH (v:Visitatore)
            WITH distinct v.eta as eta
            WHERE eta IS NOT null
            RETURN eta
            ORDER BY eta'));
        return $results;
    }
    
}
