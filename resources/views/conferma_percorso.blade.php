@extends('layouts.masterNotHome')

@section('titolo', 'CMS Brescia Musei')

@section('header')
<h1>
    Conferma percoro
</h1>
@endsection

@section('breadcrumb')
<ul class="breadcrumb pull-right">
    <li><a href="{{ route('home') }}">Home</a></li>
</ul>
@endsection


@section('corpo')


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
            
        
@endsection