@extends('layouts.master')

@section('titolo', 'CMS Brescia Musei')

@section('header')
<h1>
    Opera
</h1>
@endsection

@section('breadcrumb')
<ul class="breadcrumb pull-right">
    <li><a href="{{ route('home') }}">Home</a></li>
    <li>Opera</li>
</ul>
@endsection


@section('corpo')

<div class="row">
    <div class="col-m-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-12">
            <ul class="list-group">
                <li class="list-group-item text-center">

                        <h3><strong  >{{ $opera->get('titolo')}}</strong></h3>
                    </li>
                <li class="list-group-item"><strong>Tipologia: {{ $opera->get('tipologia')}}</strong></li>
                <li class="list-group-item " ><strong>Autore: {{ $opera->get('autore')}}</strong></li>
                <li class="list-group-item ">Anno:   {{ $opera->get('anno')}}</li>
                <li class="list-group-item ">Seolo:   {{ $opera->get('secolo')}}</li>
                <li class="list-group-item ">Luogo:   {{ $opera->get('luogo')}}</li>
                <li class="list-group-item ">Numero visite:   {{ $opera->get('visite')}}</li>
                <li class="list-group-item ">Tempo visite:   {{ $opera->get('tempo')}} secondi</li>
               
            </ul>
        
        
        </div>
</div>

        
          
        
@endsection