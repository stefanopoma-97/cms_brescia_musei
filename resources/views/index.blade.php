@extends('layouts.master')

@section('titolo', 'CMS Brescia Musei')

@section('header')
<h1>
    Crea un percorso
</h1>
@endsection

@section('breadcrumb')
<ul class="breadcrumb pull-right">
    <li><a href="{{ route('home') }}">Home</a></li>
</ul>
@endsection


@section('corpo')


        
            <form id="form_crea_percorso" name="form_crea_percorso" method="get" action="{{ route('home') }}"/>
                    
                <div class="form-group row">
                    <div class="col-md-8 col-md-offset-2 col-sm-12 col-xs-12">
                    <label for="raggruppamento">Criterio di raggruppamento:</label>
                        <select onchange="filtra_raggruppamenti(this)" class="form-control" id="raggruppamento" name="raggruppamento" placeholder="">
                            <option value="Qualsiasi">Qualsiasi</option>
                            <option value="Numero di visiste">Numero di visite</option>
                            <option value="Numero di visiste">Autore</option>
                        </select>
                    </div>
                </div>
                <div id="div_valore_raggruppamento_multi_select" class="form-group row" style='display:none'>
                    <div class="col-md-8 col-md-offset-2 col-sm-12 col-xs-12">
                    <label for="valore raggruppamento">Valore:</label>
                        <select class="form-control" id="valore_raggruppamento" name="valore_raggruppamento" placeholder="">
                            
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    @csrf
                        <div class="col-md-12" style='display:none'>
                            <div class="col-md-12 text-center">
                                <input id="mySubmit" type="submit" value='Save' class="hidden" />
                                <button onclick="filtra_raggruppamenti(this);" class="btn btn-info btn-toolbar">Filtra</button>
                            </div>
                        </div>
                </div>
            </form>
            <div class="row">
                <div class="col-md-12 text-center">
                    <button onclick="filtra_opere();" class="btn btn-info">Filtra</button>
                </div>
            </div>
            <div class="row">
                        <div class="col-md-8 col-md-offset-2 col-sm-12 col-xs-12">
                            <div class="info-banner">
                                <i class="fa fa-info info_icon"></i>
                                <h4 id="informazioni_selezione">Informazioni su opzione selezionata</h4>
                            </div>
                        </div>
            </div>




            <div class="row top-buffer">
                <div class="col-md-8 col-md-offset-1">
                    <table id="tabella_elenco_opere" class="table table-striped table-hover table-responsive  table-sm" style="width:100%" data-toggle="table" data-search="true" data-show-columns="true" >
                        <col width='10%'>
                        <col width='10%'>
                        <col width='10%'>
                        <col width='10%'>
                        <col width='10%'>
                        <col width='10%'>

                        <thead>
                            <tr>
                                <th hidden>Id</th>
                                <th>Titolo</th>
                                <th>Autore</th>
                                <th>Anno</th>
                                <th hidden>Visite</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($opere as $opera)
                            <tr class="righe_tabella_opere">
                                <td class="item_id" hidden>{{ $opera->id }}</a></td>
                                <td class="item_titolo" onclick="location.href='{{route('opera.show',['opera'=>$opera->id])}}'">{{ $opera->nome }}</td>
                                <td class="item_autore">{{ $opera->autore }}</td>
                                <td class="item_anno">{{ $opera->anno }}</td>
                                <td hidden class="item_visite">{{ $opera->visite }}</td>
                                <td class="item_bottone">
                                    <a class="btn btn-disabled" href="#"><span class="glyphicon glyphicon-plus"></span> Aggiungi</a>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                        

                    </table>
                </div>

                <div class="col-md-3 col-md-pull-1">
                    <h3> Aggiunte:</h3>
                    <table id="tabella_elenco_opere_aggiunte" class="table table-striped table-hover table-responsive  table-sm" style="width:100%" data-toggle="table" data-search="true" data-show-columns="true" >
                        <col width='10%'>
                        <col width='5%'>

                        <thead>
                            <tr>
                                <th hidden>Id</th>
                                <th>Titolo</th>
                                <th hidden></th>
                            </tr>
                        </thead>

                        <tbody>
                           

                        </tbody>
                        

                    </table>
                </div>
            </div>




<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">


    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title">Sei sicuro di voler inserire quest'opera?</h1>
            </div>
            <div class="modal-body">
                <button id="modal-button-success" type="button" onclick="tab1_To_tab2(bottone_cliccato, opere, opere_selezionate)"  class="btn btn-success" data-dismiss="modal">Conferma</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>

            </div>
            <div class="modal-footer" style="text-align:center">
                <span class="glyphicon glyphicon-info-sign"></span>
                <h5> L'opera non rispetta i criteri selezionati</h5>
            </div>
        </div> /.modal-content
    </div> /.modal-dialog

</div>






            
<script type="text/javascript">
var opere = <?php echo json_encode($opere); ?>;
var opere_selezionate = [];
var bottone_cliccato;
</script> 

    
<script type="text/javascript">
    $("#modal-button-success").click(function (e) {
        console.log("click button modal");
    });
        bottone_cliccato=e.currentTarget;
        console.log("bottone cliccato: "+bottone_cliccato);
    });

    //onclick="tab1_To_tab2(this, opere, opere_selezionate) 
</script>
            
        
@endsection