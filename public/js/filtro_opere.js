/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */


function filtra_raggruppamenti(select){
    //in base al tipo di ragruppamento modifica la seconda parte della form
    
    //button.form.submit();
    raggruppamento = (select.form.ragruppamento.value).trim();
    console.log("ragruppamento: "+raggruppamento);
    
    if (raggruppamento=="Qualsiasi"){
       $('#div_valore_ragruppamento_multi_select').hide();
       console.log("mantengo nascosti criteri");
       
       $('#informazioni_selezione').html("Hai selezionato qualsiasi....");
    }
    else if (raggruppamento=="Numero di visiste"){
        $('#div_valore_ragruppamento_multi_select').show();
        console.log("mostro criteri");
        
        $('#informazioni_selezione').html("hai selezionato numero di visite");
    }
    else{
        console.log("nulla");
    }
    
    return true;
        
}


function filtra_opere(){
    //click su filtra, in base a cosa è presente nei ragruppamenti modifica la tabella opere
    
    var tabella = document.getElementById("tabella_elenco_opere");
    var righe=tabella.rows.length; //righe
    var colonne=tabella.rows[0].cells.length; //colonne
    
    console.log("righe: "+righe);
    console.log("colonne: "+colonne);
    
    
    var i;
    var j;
    for(i=1; i<righe; i++){
        for (j=0; j<colonne; j++){
            var temp = tabella.rows[i].cells.item(j).innerHTML;
            console.log("valore: "+temp);
        }
    }
    
    //nasconde seconda colonna
    //$('#tabella_elenco_opere td:nth-child(2),#tabella_elenco_opere th:nth-child(2)').hide();
        
}


