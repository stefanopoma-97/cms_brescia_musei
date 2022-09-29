@extends('layouts.masterNotHome')

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

@if($_SERVER['REQUEST_METHOD']=="GET")
<h1>GET method</h1>
@else
<h1>POST method</h1>
@endif
        
            <form id="form_crea_percorso" name="form_crea_percorso" method="post" action="{{ route('filtro_percorsi') }}"/>
            @csrf   
                <div class="form-group row">
                    <div class="col-md-8 col-md-offset-2 col-sm-12 col-xs-12">
                    <label for="raggruppamento">Criterio di raggruppamento:</label>
                        <select onchange="filtra_raggruppamenti(this)" class="form-control" id="raggruppamento" name="raggruppamento" placeholder="">
                            @foreach ($raggruppamenti as $r)
                                @if($r == $raggruppamento)
                                    <option selected="" value="{{$r}}">{{$r}}</option>
                                @else
                                    <option value="{{$r}}">{{$r}}</option>                                
                                @endif
                            @endforeach
                            <!--<option value="Qualsiasi">Qualsiasi</option>
                            <option value="Tipologia">Tipologia</option>
                            <option  value="Data di creazione">Data di creazione</option>
                            <option value="Secolo">Secolo</option>
                            <option value="Luogo di provenienza">Luogo di provenienza</option>
                            <option value="Autore">Autore</option>
                            <option value="Visite totali">Visite totali</option>
                            <option value="Tempo totale delle visite">Tempo totale delle visite</option>
                            <option value="Visite nell'ultimo anno">Visite nell'ultimo anno</option>
                            <option value="Tempo totale delle visite dell'ultimo anno">Tempo totale delle visite dell'ultimo anno</option>
                            <option value="Meno visitate">Meno visitate</option>
                            <option value="Età visitatori">Età visitatori</option>
                            <option value="Categoria visitatori">Categoria visitatori</option>
                            <option value="Sesso visitatori">Sesso viistatori</option>-->
                        </select>
                    </div>
                </div>
                <div id="div_valore_raggruppamento_multi_select" class="form-group row" style='display:none'>
                    <div class="col-md-8 col-md-offset-2 col-sm-12 col-xs-12">
                    <label for="valore raggruppamento">Valore:</label>
                        <select class="form-control select_valori" id="valore_raggruppamento_tipologia" name="valore_raggruppamento_tipologia" placeholder="">
                            @if(empty($tipologia_selezionata))
                                @foreach ($tipologie as $c)
                                        <option value="{{$c->get('tipologia')}}">{{$c->get('tipologia')}}</option>                                
                                @endforeach   
                            @else
                                @foreach ($tipologie as $c)
                                    @if ($tipologia_selezionata == $c->get('tipologia'))
                                        <option selected value="{{$c->get('tipologia')}}">{{$c->get('tipologia')}}</option>   
                                    @else
                                        <option value="{{$c->get('tipologia')}}">{{$c->get('tipologia')}}</option>
                                    @endif
                                @endforeach   
                            @endif
                        </select>
                        <select class="form-control select_valori" id="valore_raggruppamento_data" name="valore_raggruppamento_data" placeholder="">
                            @if(empty($data_selezionata))
                                @foreach ($date as $c)
                                        <option value="{{$c->get('anno')}}">{{$c->get('anno')}}</option>                                
                                @endforeach   
                            @else
                                @foreach ($date as $c)
                                        @if ($data_selezionata == $c->get('anno'))
                                            <option selected="" value="{{$c->get('anno')}}">{{$c->get('anno')}}</option>    
                                        @else
                                            <option value="{{$c->get('anno')}}">{{$c->get('anno')}}</option> 
                                        @endif
                                @endforeach   
                            @endif
                        </select>
                        <select class="form-control select_valori" id="valore_raggruppamento_secolo" name="valore_raggruppamento_secolo" placeholder="">
                            @if(empty($secolo_selezionato))
                                @foreach ($secoli as $c)
                                        <option value="{{$c->get('secolo')}}">{{$c->get('secolo')}}</option>                                
                                @endforeach   
                            @else
                                @foreach ($secoli as $c)
                                        @if ($secolo_selezionato == $c->get('secolo'))
                                            <option selected="" value="{{$c->get('secolo')}}">{{$c->get('secolo')}}</option>   
                                        @else
                                            <option value="{{$c->get('secolo')}}">{{$c->get('secolo')}}</option>
                                        @endif
                                @endforeach  
                            @endif
                        </select>
                        <select class="form-control select_valori" id="valore_raggruppamento_luogo" name="valore_raggruppamento_luogo" placeholder="">
                            @if(empty($luogo_selezionato))
                                @foreach ($luoghi as $c)
                                        <option value="{{$c->get('luogo')}}">{{$c->get('luogo')}}</option>                                
                                @endforeach   
                            @else
                                @foreach ($luoghi as $c)
                                        @if ($luogo_selezionato == $c->get('luogo'))
                                            <option selected="" value="{{$c->get('luogo')}}">{{$c->get('luogo')}}</option>    
                                        @else
                                            <option value="{{$c->get('luogo')}}">{{$c->get('luogo')}}</option> 
                                        @endif
                                @endforeach 
                            @endif
                        </select>
                        <select class="form-control select_valori" id="valore_raggruppamento_autore" name="valore_raggruppamento_autore" placeholder="">
                            @if(empty($autore_selezionato))
                                @foreach ($autori as $c)
                                        <option value="{{$c->get('id')}}">{{$c->get('nome')}}</option>                                
                                @endforeach   
                            @else
                                @foreach ($autori as $c)
                                        @if ($autore_selezionato == $c->get('id'))
                                            <option selected="" value="{{$c->get('id')}}">{{$c->get('nome')}}</option>    
                                        @else
                                            <option value="{{$c->get('id')}}">{{$c->get('nome')}}</option>  
                                        @endif
                                @endforeach
                            @endif
                        </select>
                       
                        <select class="form-control select_valori" id="valore_raggruppamento_eta" name="valore_raggruppamento_eta" placeholder="">
                            @if(empty($eta_selezionata))
                                @foreach ($eta as $c)
                                        <option value="{{$c->get('eta')}}">{{$c->get('eta')}}</option>                                
                                @endforeach   
                            @else
                                @foreach ($eta as $c)
                                        @if ($eta_selezionata == $c->get('eta'))
                                            <option selected="" value="{{$c->get('eta')}}">{{$c->get('eta')}}</option>   
                                        @else
                                            <option value="{{$c->get('eta')}}">{{$c->get('eta')}}</option>  
                                        @endif
                                @endforeach
                            @endif
                        </select>
                        <select class="form-control select_valori" id="valore_raggruppamento_categoria" name="valore_raggruppamento_categoria" placeholder="">
                            @if(empty($categoria_selezionata))
                                @foreach ($categorie as $c)
                                        <option value="{{$c->get('id')}}">{{$c->get('nome')}}</option>                                
                                @endforeach   
                            @else
                                @foreach ($categorie as $c)
                                        @if ($categoria_selezionata == $c->get('id'))
                                            <option selected="" value="{{$c->get('id')}}">{{$c->get('nome')}}</option>    
                                        @else
                                            <option value="{{$c->get('id')}}">{{$c->get('nome')}}</option>   
                                        @endif
                                @endforeach
                            @endif
                        </select>
                        <select class="form-control select_valori" id="valore_raggruppamento_sesso" name="valore_raggruppamento_sesso" placeholder="">
                            @if(empty($sesso_selezionato))
                                <option value="M">Maschio</option>
                                <option value="F">Femmina</option>
                                <option value="N">Non specificato</option>
                            @else
                                @if ($sesso_selezionato == 'M')
                                    <option selected value="M">Maschio</option>
                                @else
                                    <option value="M">Maschio</option>
                                @endif

                                @if ($sesso_selezionato == 'F')
                                    <option selected value="F">Femmina</option>
                                @else
                                    <option value="F">Femmina</option>
                                @endif

                                @if ($sesso_selezionato == 'N')
                                    <option selected value="N">Non specificato</option>
                                @else
                                    <option value="N">Non specificato</option>
                                @endif
                            @endif
                            
                            
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    @csrf
                        <div class="col-md-12" >
                            <div class="col-md-12 text-center">
                                <input id="mySubmit" type="submit" value='Save' class="hidden" />
                                <button onclick="filtra_opere_db(opere_selezionate);" class="btn btn-info btn-toolbar">Filtra</button>
                            </div>
                        </div>
                </div>
            </form>

            <div class="row" style='display:none'>
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

                        <thead id="tabella_elenco_opere_head">
                            <tr>
                                <th class="item_id" hidden>Id</th>
                                <th class="item_titolo">Titolo</th>
                                <th class="item_tipologia">Tipologia</th>
                                <th class="item_autore">Autore</th>
                                <th hidden class="item_anno">Anno</th>
                                <th hidden class="item_secolo">Secolo</th>
                                <th hidden class="item_luogo">Luogo</th>
                                <th hidden class="item_visite">Visite</th>
                                <th hidden class="item_tempo">Tempo</th>
                                <th hidden class="item_per_categoria">% Categoria</th>
                                <th hidden class="item_per_eta">>% Età</th>
                                <th hidden class="item_per_sesso">% Sesso</th>
                                <th hidden class="item_valore">VALORE</th>
                                <th></th>
                                <th hidden=""></th>
                            </tr>
                        </thead>

                        <tbody id="tabella_elenco_opere_body">
                            @foreach($opere as $opera)
                            <tr class="righe_tabella_opere">
                                <td class="item_id" hidden>{{ $opera->get('id') }}</a></td>
                                <td class="item_titolo" onclick="location.href='{{route('opera.show',['opera'=> $opera->get('id')])}}'">{{  $opera->get('titolo') }}</td>
                                <td class="item_tipologia">{{ $opera->get('tipologia') }}</td>
                                <td class="item_autore">{{ $opera->get('autore') }}</td>
                                <td hidden class="item_anno">{{ $opera->get('anno') }}</td>
                                <td hidden class="item_secolo">{{ $opera->get('secolo') }}</td>
                                <td hidden class="item_luogo">{{ $opera->get('luogo') }}</td>
                                <td hidden class="item_visite">{{ $opera->get('visite') }}</td>
                                <td hidden class="item_tempo">{{ $opera->get('tempo') }}</td>
                                <td hidden class="item_per_categoria">{{ $opera->get('per_categoria') }}</td>
                                <td hidden class="item_per_eta">{{ $opera->get('per_eta') }}</td>
                                <td hidden class="item_per_sesso">{{ $opera->get('per_sesso') }}</td>
                                <td hidden class="item_valore"></td>
                                @if($_SERVER['REQUEST_METHOD']=="GET")
                                <td class="item_bottone">
                                    <a class="btn btn-disabled" href="#"><span class="glyphicon glyphicon-plus"></span> Aggiungi</a>
                                </td>
                                @else
                                <td class="item_bottone">
                                    <a class="btn btn-success" href="#" onclick="move_tab1_to_tab2(this, opere, opere_selezionate, raggruppamento)"><span class="glyphicon glyphicon-plus"></span> Aggiungi</a>
                                </td>
                                @endif
                                <td class="item_bottone_delete" hidden><a class='btn btn-default' onclick='move_tab2_to_tab1(this, opere, opere_selezionate, raggruppamento)'><span class='glyphicon glyphicon-remove'></span></a></td>
                                
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

                        <thead id="tabella_elenco_opere_aggiunte_head">
                            <tr>
                                <th class="item_id" hidden>Id</th>
                                @if($_SERVER['REQUEST_METHOD']=="GET")
                                    <th class="item_titolo" hidden>Titolo</th>
                                    @else
                                    <th class="item_titolo">Titolo</th>
                                @endif
                                <th hidden class="item_tipologia">Tipologia</th>
                                <th hidden class="item_autore">Autore</th>
                                <th hidden class="item_anno">Anno</th>
                                <th hidden class="item_secolo">Secolo</th>
                                <th hidden class="item_luogo">Luogo</th>
                                <th hidden class="item_visite">Visite</th>
                                <th hidden class="item_tempo">Tempo</th>
                                <th hidden class="item_per_categoria">% Categoria</th>
                                <th hidden class="item_per_eta">% Età</th>
                                <th hidden class="item_per_sesso">% Sesso</th>
                                <th hidden class="item_valore">VALORE</th>
                                <th hidden class="item_bottone"></th>
                                <th class="item_bottone_delete"></th>
                            </tr>
                        </thead>

                        <tbody id="tabella_elenco_opere_aggiunte_body">
                            @foreach($opere_selezionate as $op)
                            <tr class="righe_tabella_opere_selezionate">
                                <td class="item_id" hidden>{{ $op->get('id') }}</a></td>
                                <td class="item_titolo" onclick="location.href='{{route('opera.show',['opera'=>$op->get('id')])}}'">{{ $op->get('titolo') }}</td>
                                <td hidden class="item_tipologia">{{ $opera->get('tipologia') }}</td>
                                <td hidden class="item_autore">{{ $opera->get('autore') }}</td>
                                <td hidden class="item_anno">{{ $opera->get('anno') }}</td>
                                <td hidden class="item_secolo">{{ $opera->get('secolo') }}</td>
                                <td hidden class="item_luogo">{{ $opera->get('luogo') }}</td>
                                <td hidden class="item_visite">{{ $opera->get('visite') }}</td>
                                <td hidden class="item_tempo">{{ $opera->get('tempo') }}</td>
                                <td hidden class="item_per_categoria">{{ $opera->get('per_categoria') }}</td>
                                <td hidden class="item_per_eta">{{ $opera->get('per_eta') }}</td>
                                <td hidden class="item_per_sesso">{{ $opera->get('per_sesso') }}</td>
                                <td hidden class="item_valore"></td>
                                @if($_SERVER['REQUEST_METHOD']=="GET")
                                <td class="item_bottone" hidden="">
                                    <a class="btn btn-disabled" href="#"><span class="glyphicon glyphicon-plus"></span> Aggiungi</a>
                                </td>
                                @else
                                <td class="item_bottone" hidden="">
                                    <a class="btn btn-success" href="#" onclick="move_tab1_to_tab2(this, opere, opere_selezionate, raggruppamento)"><span class="glyphicon glyphicon-plus"></span> Aggiungi</a>
                                </td>
                                @endif
                                <td class="item_bottone_delete"><a class='btn btn-default' onclick='move_tab2_to_tab1(this, opere, opere_selezionate, raggruppamento)'><span class='glyphicon glyphicon-remove'></span></a></td>
                                
                            </tr>
                            
                            
                           
                           @endforeach
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
                <button id="modal-button-success" type="button" class="btn btn-success" data-dismiss="modal">Conferma</button>
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
filtra_raggruppamenti(document.getElementById("raggruppamento"));
var opere = <?php echo json_encode($opere); ?>;
var opere_selezionate = <?php echo json_encode($opere_selezionate)?>;
var raggruppamento = <?php echo json_encode($raggruppamento)?>;
cancella_colonne_tab1(raggruppamento);
</script> 
        
@endsection