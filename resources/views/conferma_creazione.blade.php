@extends('layouts.master')

@section('titolo', 'CMS Brescia Musei')

@section('header')
<h1>
    Confermata la creazione
</h1>

@endsection

@section('breadcrumb')
<ul class="breadcrumb pull-right">
    <li><a href="{{ route('home') }}">Home</a></li>
</ul>
@endsection


@section('corpo')

<div class="container text-center">
    <div class="row" style="margin-top: 4em;">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel text-center">
                    <div class='panel-heading alert alert-success'>
                        Percorso creato
                    </div>
                    <div class='panel-body text-center'>
                        <p class="text-center"><a class="btn btn-default" href="{{ route('home') }}"><span class='glyphicon glyphicon-log-out'></span> Ritorna alla Home</a></p>
                    </div>
                </div>
            </div>
    </div>
</div>

        
          
        
@endsection


