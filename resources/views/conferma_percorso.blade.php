@extends('layouts.masterNotHome')

@section('titolo', 'CMS Brescia Musei')

@section('header')
<h1>
    Conferma percorso
</h1>
@endsection

@section('breadcrumb')
<ul class="breadcrumb pull-right">
    <li><a href="{{ route('home') }}">Home</a></li>
</ul>
@endsection


@section('corpo')

<div class="row">
    <form id="form_conferma_percorso" name="form_conferma_percorso" method="post" action="{{ route('crea_percorso') }}"/>
    @csrf   
        <div class="form-group row">
            <div class="col-md-10 col-md-offset-1 col-sm-12 col-xs-12">
                    <label for="titolo">Titolo del percorso:</label>
                    <input maxlength="32" class="form-control" id="titolo" required="True" name="titolo" placeholder="Inserisci la durata il titolo">
            </div>
        </div>
        
        <div class="form-group row">
            <div class="col-md-10 col-md-offset-1 col-sm-12 col-xs-12">
                    <label for="titolo">Descrizione percorso:</label>
                    <textarea maxlength="1000" class="form-control" id="descrizione" required="True" name="descrizione" placeholder="Inserisci la descrizione il titolo"></textarea>
            </div>
        </div>
    
        
        <div class="form-group row">
            @csrf
                <div class="col-md-12" >
                    <div class="col-md-12 text-center">
                        <input id="mySubmit" type="submit" value='Save' class="hidden" />
                        <button class="btn btn-info btn-toolbar">Conferma</button>
                        <a href="{{route('home')}}" class="btn btn-danger btn-toolbar"><span class="glyphicon glyphicon-log-out"></span> Annulla    </a>
                    </div>
                </div>
        </div>
    </form>
</div>

<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <table class="table table-striped table-hover table-responsive  table-sm" style="width:100%" data-toggle="table" data-search="true" data-show-columns="true" >
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
                    <th class="item_anno">Anno</th>
                    <th  class="item_secolo">Secolo</th>
                    <th  class="item_luogo">Luogo</th>
                    <th hidden class="item_visite">Visite</th>
                    <th hidden class="item_tempo">Tempo</th>
                    
                </tr>
            </thead>

            <tbody id="tabella_elenco_opere_body">
                @foreach($opere as $opera)
                <tr class="righe_tabella_opere">
                    <td class="item_id" hidden>{{ $opera->get('id') }}</a></td>
                    <td class="item_titolo"><a href="{{route('opera.show',['opera'=> $opera->get('id')])}}'" target="_blank">{{  $opera->get('titolo') }}</a></td>
                    <td class="item_tipologia">{{ $opera->get('tipologia') }}</td>
                    <td class="item_autore">{{ $opera->get('autore') }}</td>
                    <td  class="item_anno">{{ $opera->get('anno') }}</td>
                    <td  class="item_secolo">{{ $opera->get('secolo') }}</td>
                    <td  class="item_luogo">{{ $opera->get('luogo') }}</td>
                    <td hidden class="item_visite">{{ $opera->get('visite') }}</td>
                    <td hidden class="item_tempo">{{ $opera->get('tempo') }}</td>

                </tr>
                @endforeach

            </tbody>


        </table>
    </div>
</div>   

<script type="text/javascript">
var opere = <?php echo json_encode($opere); ?>;
inserisci_opere_in_conferma_percorso(opere);
</script> 
            
        
@endsection