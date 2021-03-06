@extends('adminlte::page')


@section('title', 'Meu Perfil')

@section('content_header')

    <h1>Meu Perfil</h1>

@endsection

@section('content')

    @if($errors->any())

        <div class="alert alert-danger">
            <ul>
                <h5>
                    <i class="icon fas fa-ban"></i>
                    Ocorreu um erro.
                </h5>
                @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>

    @endif

    @if(session('success'))
        <div class="alert alert-success">{{session('success')}}</div>
    @endif

    <div class="card">

        <div class="card-body">
            <form action="{{route('profile.save')}}" method="POST" class="form-horizontal" onsubmit="return confirm('Tem certeza que deseja editar?')">
                @csrf
                @method('PUT')
                
                <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nome completo</label>
                        <div class="col-sm-10">
                            <input type="text" name="name" value="{{$user->name}}" class="form-control @error('name') is-invalid @enderror" />
                        </div>
                </div>
                <div class="form-group row">
                        <label class="col-sm-2 col-form-label">E-mail</label>
                        <div class="col-sm-10">
                            <input type="email" name="email" value="{{$user->email}}" class="form-control @error('email') is-invalid @enderror" />
                        </div>
                </div>
                <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nova Senha</label>
                        <div class="col-sm-10">
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" />
                        </div>
                </div>
                <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Senha novamente</label>
                        <div class="col-sm-10">
                            <input type="password" name="password_confirmation" class="form-control @error('password') is-invalid @enderror" />
                        </div>
                </div>
                <div class="form-group row">
                        <label class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-10">
                            <input type="submit" value="Salvar" class="btn btn-success" />
                        </div>
                </div>
            </form>
        </div>
    </div>


@endsection


@section('css')
    <!--<h3>Caso queira mudar o css de alguma coisa</h3>
    <link rel="stylesheet" href="/assets/css/custom.css" />-->
@endsection

@section('js')
    <!--<script>alert("Rodando")</script>-->
@endsection