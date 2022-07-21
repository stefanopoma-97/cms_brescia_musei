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
                    <label for="ragruppamento">Criterio di ragruppamento:</label>
                        <select onchange="filtra_raggruppamenti(this)" class="form-control" id="ragruppamento" name="ragruppamento" placeholder="">
                            <option value="Qualsiasi">Qualsiasi</option>
                            <option value="Numero di visiste">Numero di visite</option>
                            <option value="Numero di visiste">Fascia di età</option>
                        </select>
                    </div>
                </div>
                <div id="div_valore_ragruppamento_multi_select" class="form-group row" style='display:none'>
                    <div class="col-md-8 col-md-offset-2 col-sm-12 col-xs-12">
                    <label for="valore ragruppamento">Valore:</label>
                        <select class="form-control" id="valore_ragruppamento" name="valore_ragruppamento" placeholder="">
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
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



            <div class="row top-buffer"/> 

            <div class="row">
                <div class="col-md-8 col-md-offset-1">
                    <table id="tabella_elenco_opere" class="table table-striped table-hover table-responsive  table-sm" style="width:100%" data-toggle="table" data-search="true" data-show-columns="true" >
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
                                <th>Visite</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($opere as $opera)
                            <tr>
                                <td hidden>{{ $opera->id }}</a></td>
                                <td onclick="location.href='{{route('opera.show',['opera'=>$opera->id])}}'">{{ $opera->nome }}</td>
                                <td>{{ $opera->autore }}</td>
                                <td>{{ $opera->visite }}</td>
                                <td>
                                    <a class="btn btn-default" href="#"><span class="glyphicon glyphicon-plus"></span> Aggiungi</a>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                        

                    </table>
                </div>
            </div>






            
            
            
        
@endsection