/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */


function filtra_raggruppamenti(select){
    //in base al tipo di ragruppamento modifica la seconda parte della form
    
    //button.form.submit();
    raggruppamento = (select.form.raggruppamento.value).trim();
    console.log("raggruppamento: "+raggruppamento);
    
    if (raggruppamento=="Qualsiasi"){
       $('#div_valore_ragruppamento_multi_select').hide();
       console.log("mantengo nascosti criteri");
       
       $('#informazioni_selezione').html("Hai selezionato qualsiasi....");
    }
    else if (raggruppamento=="Numero di visiste"){
        $('#valore_raggruppamento').append(new Option("Maggiori di 500", 500));
        $('#valore_raggruppamento').append(new Option("Maggiori di 1000", 1000));
        $('#div_valore_raggruppamento_multi_select').show();
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
    
    
    var form = document.getElementById("form_crea_percorso");
    var raggruppamento = (form.raggruppamento.value).trim();
    var valore_raggruppamento = (form.valore_raggruppamento.value).trim();
    console.log("ragruppamento: "+raggruppamento);
    console.log("valore_raggruppamento: "+valore_raggruppamento);
    
    
    
    if (raggruppamento=="Qualsiasi"){
        filtra_opere_qualsiasi();
    }
    else if (raggruppamento=="Numero di visiste"){
        filtra_opere_visite(valore_raggruppamento);
    }
    
    //nasconde seconda colonna
    //$('#tabella_elenco_opere td:nth-child(2),#tabella_elenco_opere th:nth-child(2)').hide();
        
}


function filtra_opere_qualsiasi(){
    var show = ["2", "3", "4"];
    var hide = ["1", "5"];
    
    var string_show="";
    var string_hide="";
    
    show.forEach(function (item, index) {
        if (index == 0)
            string_show = string_show+"#tabella_elenco_opere td:nth-child("+item+"),#tabella_elenco_opere th:nth-child("+item+")";
        else
            string_show = string_show+",#tabella_elenco_opere td:nth-child("+item+"),#tabella_elenco_opere th:nth-child("+item+")";
      });
      
    hide.forEach(function (item, index) {
        if (index == 0)
            string_hide = string_hide+"#tabella_elenco_opere td:nth-child("+item+"),#tabella_elenco_opere th:nth-child("+item+")";
        else
            string_hide = string_hide+",#tabella_elenco_opere td:nth-child("+item+"),#tabella_elenco_opere th:nth-child("+item+")";
      });
    
    console.log("string show: "+string_show);
    console.log("string show: "+string_hide);
    
    $(string_show).show();
    $(string_hide).hide();
    
    $('#tabella_elenco_opere td a').attr("class", "btn btn-success");
    
}

function filtra_opere_visite(valore){
    var show = ["2", "3", "5"];
    var hide = ["1", "4"];
    
    var string_show="";
    var string_hide="";
    
    show.forEach(function (item, index) {
        if (index == 0)
            string_show = string_show+"#tabella_elenco_opere td:nth-child("+item+"),#tabella_elenco_opere th:nth-child("+item+")";
        else
            string_show = string_show+",#tabella_elenco_opere td:nth-child("+item+"),#tabella_elenco_opere th:nth-child("+item+")";
      });
      
    hide.forEach(function (item, index) {
        if (index == 0)
            string_hide = string_hide+"#tabella_elenco_opere td:nth-child("+item+"),#tabella_elenco_opere th:nth-child("+item+")";
        else
            string_hide = string_hide+",#tabella_elenco_opere td:nth-child("+item+"),#tabella_elenco_opere th:nth-child("+item+")";
      });
    
    console.log("string show: "+string_show);
    console.log("string show: "+string_hide);
    
    $(string_show).show();
    $(string_hide).hide();
    
    $('#tabella_elenco_opere td a').attr("class", "btn btn-success");
    
    $('.righe_tabella_opere').each(function(){
        var visite = $(this).find('.item_visite').html();
        var numero_visite = Number( visite.replace(/[^0-9\.]+/g,""));
        console.log("ottengo val visite: "+numero_visite);
        if(numero_visite >= valore){
            console.log("Valore visite maggiore di: "+valore);
            $(this).find('.item_bottone a').attr("class", "btn btn-success");
        }
        else {
            console.log("Valore visite minore di: "+valore);
            $(this).find('.item_bottone a').attr("class", "btn btn-default");
            $(this).find('.item_bottone a').attr("data-toggle", "modal");
            $(this).find('.item_bottone a').attr("data-target", "#modalForm");
        }
        });
           
    //$('#tabella_elenco_opere td a').attr("class", "btn btn-success");

    
}
