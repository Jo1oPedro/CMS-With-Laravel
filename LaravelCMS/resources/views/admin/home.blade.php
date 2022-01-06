@extends('adminlte::page')


@section('title', 'Painel')

@section('content_header')

    <h1>Painel de Controle</h1>

@endsection

@section('content')

    Olá {{ Auth::user()->name }}
    <h2>Conteúdo da minha pagina</h2>

@endsection


@section('css')
    <!--<h3>Caso queira mudar o css de alguma coisa</h3>
    <link rel="stylesheet" href="/assets/css/custom.css" />-->
@endsection

@section('js')
    <!--<script>alert("Rodando")</script>-->
@endsection