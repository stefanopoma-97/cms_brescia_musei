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


<h1>{{ $opera->id }}</h1>
<h1>{{ $opera->nome}}</h1>
        
          
        
@endsection