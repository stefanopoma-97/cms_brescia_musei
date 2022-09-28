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
        
        $client = ClientBuilder::create()
        ->withDriver('bolt', 'bolt://neo4j:neo4j_cms_brescia@localhost') // creates a bolt driver
        ->withDefaultDriver('bolt')
        ->build();
        
        //$result = ($client->run('MATCH (x) RETURN x as ritorno'));
        
        //->withDriver('https', 'https://test.com', Authenticate::basic('neo4j', 'neo4j_cms_brescia')) // creates an http driver
        //->withDriver('neo4j', 'neo4j://neo4j.test.com?database=my-database', Authenticate::oidc('token')) // creates an auto routed driver with an OpenID Connect token
        
        
        $opera1 = new Opera("1", "nome1", "autore1", "anno1", "100");
        $opera2 = new Opera("2","nome2", "autore2", "anno2", "2000");
        $opera3 = new Opera("3","nome3", "autore3", "anno3", "500");
        
       
        $opere = array($opera1, $opera2, $opera3);
        return $opere;
    }
    
    public function getOpereMenoSelezionate($array_id) {
        $opera1 = new Opera("1", "nome1", "autore1", "anno1", "100");
        $opera2 = new Opera("2","nome2", "autore2", "anno2", "2000");
        $opera3 = new Opera("3","nome3", "autore3", "anno3", "500");
        
        $opere = array();
        $temp = array($opera1, $opera2, $opera3);
        foreach ($temp as $value) {
            if(!(in_array($value->id, $array_id)))
                array_push($opere, $value);
          }
        
        return $opere;
    }
    
    public function getOpereSelezionate($array_id) {
        $opera1 = new Opera("1", "nome1", "autore1", "anno1", "100");
        $opera2 = new Opera("2","nome2", "autore2", "anno2", "2000");
        $opera3 = new Opera("3","nome3", "autore3", "anno3", "500");
        
       
        $opere = array();
        $temp = array($opera1, $opera2, $opera3);
        foreach ($temp as $value) {
            if((in_array($value->id, $array_id)))
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
        foreach ($array as $value) {
            array_push($id, $value['id']);
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
        
        $results = ($this->client->run('MATCH (a:Autore)
            WITH a.nome as nome, a.id as id
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
