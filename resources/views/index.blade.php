@extends('layouts.master')

@section('titolo', 'CMS Brescia Musei')

@section('header')
<h1>
    Home
</h1>
@endsection

@section('breadcrumb')

@endsection


@section('corpo')

<div class="form-group row">
    <div class="col-md-12 col-sm-12 col-xs-12 text-center" style="margin-top: 10em;">
        <a class="btn btn-info" href="{{ route('filtro_percorsi') }}">Crea un nuovo percorso</a>
    </div>
</div>

<h2 id="caricamento_ajax">Caricamento informazioni ...</h2>
<h2 id="caricamento_finito_ajax">Informazioni caricate</h2>


<script type="text/javascript">
//ajax_parametri_filtri();
ajax();
</script> 
        
@endsection