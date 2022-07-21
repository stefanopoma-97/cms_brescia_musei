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

//AUTENTICAZIONE
class DataLayer extends Model
{
    public function getOpere() {
        $opera1 = new Opera("1", "nome1", "autore1", "anno1", "100");
        $opera2 = new Opera("2","nome2", "autore2", "anno2", "2000");
        $opera3 = new Opera("3","nome3", "autore3", "anno3", "500");
        
       
        $opere = array($opera1, $opera2, $opera3);
        return $opere;
    }
    
}
