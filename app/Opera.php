<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App;

/**
 * Description of Opera
 *
 * @author stefa
 */
class Opera {
    //put your code here
    var $id, $nome, $autore, $anno, $visite;
    function __construct($id, $nome, $autore, $anno, $visite){
        $this->id = $id;
        $this->nome = $nome;
        $this->autore = $autore;
        $this->anno = $anno;
        $this->visite = $visite;
    }
    
    
    function nome(){
        return $this->nome;
    }
    function autore(){
        return $this->autore;
    }
    function visite(){
        return $this->visite;
    }
    function anno(){
        return $this->anno;
    }
    
    function id(){
        return $this->id;
    }
}
