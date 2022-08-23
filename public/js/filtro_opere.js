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
       $('#div_valore_raggruppamento_multi_select').hide();
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

function filtra_opere_db(opere_selezionate){
    var myForm = document.getElementById('form_crea_percorso');
    var hiddenInput = document.createElement('input');

    hiddenInput.type = 'hidden';
    hiddenInput.name = 'opere_selezionate';
    hiddenInput.value = JSON.stringify(opere_selezionate);
    myForm.appendChild(hiddenInput);
    
    console.log("array opere selezionate: "+opere_selezionate);
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
    $('#tabella_elenco_opere td a').attr("onclick", "tab1_To_tab2(this, opere, opere_selezionate)");
    
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
            $(this).find('.item_bottone a').attr("onclick", "tab1_To_tab2(this, opere, opere_selezionate)");
        }
        else {
            console.log("Valore visite minore di: "+valore);
            $(this).find('.item_bottone a').attr("class", "btn btn-default submit-modal");
            //$(this).find('.item_bottone a').attr("data-toggle", "modal");
            //$(this).find('.item_bottone a').attr("data-target", "#modalForm");
            $(this).find('.item_bottone a').attr("onclick", "click_modal(this, opere, opere_selezionate)");
        }
        });
           
    //$('#tabella_elenco_opere td a').attr("class", "btn btn-success");

    
}

function click_modal(bottone, opere, opere_selezionate){
    
    console.log("click button modal");
    
    $('#modalForm').modal('show');
    
    
    
    
    $('#modal-button-success').click(function(){
        tab1_To_tab2(bottone, opere, opere_selezionate);
    });
}

function move_tab1_to_tab2(bottone, array_opere, array_opere_selezionate){
    console.log("array opere: "+array_opere);
    console.log("array opere selezionate: "+array_opere_selezionate);
    console.log("bottone cliccato: "+$(bottone).html());
    var table2 = $("#tabella_elenco_opere_aggiunte");
    var body_table2 = $("#tabella_elenco_opere_aggiunte_body");
    var riga = $(bottone).parent().parent();
    var id = $(bottone).parent().parent().find('.item_id').html();
    
    var j=-1;
    for (let i = 0; i < array_opere.length; i++){
        if (array_opere[i].id == id)
            j=i;
    }
    if (j!=-1){
        
        body_table2.append(riga);
       
        array_opere_selezionate.push(array_opere[j]);
        array_opere.splice(j, 1); 
        
        //fa comparire pulsante X
        $('#tabella_elenco_opere_aggiunte_body td.item_bottone_delete').each(function() {
              $(this).show();
          });

        $('#tabella_elenco_opere_aggiunte_body td.item_autore').each(function() {
              $(this).hide();
          });
        $('#tabella_elenco_opere_aggiunte_body td.item_anno').each(function() {
              $(this).hide();
          });

        $('#tabella_elenco_opere_aggiunte_body td.item_bottone').each(function() {
              $(this).hide();
          });
    }
    
    
    
    
    console.log("array opere: "+array_opere);
    console.log("array opere selezionate: "+array_opere_selezionate);
    
}

function move_tab2_to_tab1(bottone, array_opere, array_opere_selezionate){
    console.log("array opere: "+array_opere);
    console.log("array opere selezionate: "+array_opere_selezionate);
    console.log("bottone cliccato: "+$(bottone).html());
    var table1 = $("#tabella_elenco_opere");
    var body_table1 = $("#tabella_elenco_opere_body");
    var riga = $(bottone).parent().parent();
    var id = $(bottone).parent().parent().find('.item_id').html();
    
    var j=-1;
    for (let i = 0; i < array_opere_selezionate.length; i++){
        if (array_opere_selezionate[i].id == id)
            j=i;
    }
    if (j!=-1){
        
        body_table1.append(riga);
       
        array_opere.push(array_opere_selezionate[j]);
        array_opere_selezionate.splice(j, 1); 
        
        //fa comparire pulsante X
        $('#tabella_elenco_opere_body td.item_bottone_delete').each(function() {
              $(this).hide();
          });

        $('#tabella_elenco_opere_body td.item_autore').each(function() {
              $(this).show();
          });
        $('#tabella_elenco_opere_body td.item_anno').each(function() {
              $(this).show();
          });

        $('#tabella_elenco_opere_body td.item_bottone').each(function() {
              $(this).show();
          });
    }
    
    
    
    
    console.log("array opere: "+array_opere);
    console.log("array opere selezionate: "+array_opere_selezionate);
    
}


function tab1_To_tab2(bottone, array_opere, array_opere_selezionate)
{
    console.log("array opere: "+array_opere);
    console.log("array opere selezionate: "+array_opere_selezionate);
    console.log("bottone cliccato: "+$(bottone).html());
    var table2 = document.getElementById("tabella_elenco_opere_aggiunte");
    
    var id = $(bottone).parent().parent().find('.item_id').html();
    var titolo = $(bottone).parent().parent().find('.item_titolo').html();
    
    console.log("Titolo: "+titolo);
    console.log("ID: "+id);
    
   
    
    var j=-1;
    for (let i = 0; i < array_opere.length; i++){
        if (array_opere[i].id == id)
            j=i;
    }
    if (j!=-1){
        
        $("#tabella_elenco_opere_aggiunte tbody").prepend("<tr><td class='item_id' hidden>"
            +id+"</td><td class='item_titolo'>"
            +titolo+"</td><td><a class='btn btn-default' onclick='tab2_To_tab1(this, opere, opere_selezionate)'><span class='glyphicon glyphicon-remove'></span></a></td></tr>");
                           
        $(bottone).parent().parent().hide();
        
        array_opere_selezionate.push(array_opere[j]);
        array_opere.splice(j, 1); 
    }
    
    
    console.log("array opere: "+array_opere);
    console.log("array opere selezionate: "+array_opere_selezionate);
        
}

function tab2_To_tab1(bottone, array_opere, array_opere_selezionate)
{
    console.log("array opere: "+array_opere);
    console.log("array opere selezionate: "+array_opere_selezionate);
    
    var table1 = document.getElementById("tabella_elenco_opere");
    
    var id = $(bottone).parent().parent().find('.item_id').html();
    
    var j=-1;
    for (let i = 0; i < array_opere_selezionate.length; i++){
        if (array_opere_selezionate[i].id == id)
            j=i;
    }
    if (j!=-1){
        
        var opera = array_opere_selezionate[j];
        console.log("id opera trovato in array: "+opera.id);
        $('.righe_tabella_opere').each(function(){
        var id = $(this).find('.item_id').html();
        var numero_id = Number( id.replace(/[^0-9\.]+/g,""));
        console.log("numero id: "+numero_id);
        if(numero_id == opera.id){
            console.log("trovata corrispondenza con colonna");
            $(this).show();
        }
        
        });
       
        
        $(bottone).parent().parent().remove();

        array_opere.push(array_opere_selezionate[j]);
        array_opere_selezionate.splice(j, 1);
        
        filtra_opere();
    }
    
    console.log("array opere: "+array_opere);
    console.log("array opere selezionate: "+array_opere_selezionate);
    
    
    
    
    
   
        
}


function array_opere(opere){
    console.log("array opere: "+opere);
}


