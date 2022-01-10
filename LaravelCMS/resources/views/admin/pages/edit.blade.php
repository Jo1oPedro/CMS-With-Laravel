@extends('adminlte::page')

@section('title', 'Editar Página')

@section('content_header')
    <h1>
        Editar Página
    </h1>
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

    

    <div class="card">

        <div class="card-body">
            <form action="{{route('pages.update', ['page' => $page->id])}}" method="POST" class="form-horizontal" onsubmit="return confirm('Tem certeza que deseja editar?')">
                @csrf
                @method('PUT')
                <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Título</label>
                        <div class="col-sm-10">
                            <input type="text" name="title" value="{{$page->title}}" class="form-control @error('title') is-invalid @enderror" />
                        </div>
                </div>
                <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Corpo</label>
                        <div class="col-sm-10">
                            <textarea name="body" class="form-control bodyfield">{{$page->body}}</textarea>
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
    
<script src="https://cdn.tiny.cloud/1/abc5x0ae2zl73nx3tya3h6koo3t4y3tmlka8cz37kh6xrxs7/tinymce/5/tinymce.min.js"></script>
<script>

    tinymce.init({
        selector: 'textarea.bodyfield',
        height:300,
        menubar:false,
        plugins:['link', 'table', 'image', 'autoresize', 'lists'],
        toolbar:'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | table | link image | bullist numlist',
        content_css:[ 
            '{{asset('assets/css/content.css')}}'
        ],
        images_upload_url:'{{route('imageupload')}}',
        //passa pela api
        images_upload_credentials:true,
        //só permite que usuarios logados possam enviar imagens
        convert_urls:false
        //não deixa converter a url para uma relativa,
        //o que iria prejudicar o site na hora de exibir
        //em outra página
    });

</script>


@endsection