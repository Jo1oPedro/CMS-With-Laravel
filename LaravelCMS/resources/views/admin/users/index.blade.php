@extends('adminlte::page')

@section('title', 'Usuários')

@section('content_header')
    <h1>
        Meus Usuários
        <a href="{{route('users.create')}}" class="btn btn-sm btn-success">Novo usuario</a>
    </h1>
@endsection

@section('content')

    @if(session('success'))
        <div class="alert alert-success">{{session('success')}}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user) 
                    <tr>
                        <td>{{$user->id}}</td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>
                            
                            <a href="{{route('users.edit', ['user' => $user->id])}}" class="btn btn-sm btn-info"> Editar </a>
                            
                            @if($user->id != $loggedId)
                                <form class="d-inline" method="POST" action="{{route('users.destroy', ['user' => $user->id])}}" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                                    @method('DELETE')
                                    @csrf
                                    <button class="btn btn-sm btn-danger">Excluir</button>
                                </form>
                            @endif     
                        </td>
                        </tr>
            
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    {{ $users->links('pagination::bootstrap-4') }}
    <script src="sweetalert2.all.min.js"></script>
@endsection