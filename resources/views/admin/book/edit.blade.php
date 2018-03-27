@extends('admin.layout.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Livro {{$book->title}}</div>
                @if($errors)
                    <p class="text text-danger">{{$errors->first()}}</p>
                @endif
                <div class="panel-body">
                    <form action="{{ route('book.update', $book->id) }}" method="POST" enctype = "multipart/form-data">
                        {!! method_field('PUT') !!}
                        {!! csrf_field() !!}
                        <ul class="list-group">
                            @if($book->copies()->count()>0)
                                <li class="list-group-item">
                                    <h4 class="text text-warning">Alterar este livro alterará TODOS os {{ $book->copies()->count() }} exemplares cadastrados</h4>
                                </li>
                            @endif
                            <li class="list-group-item">
                                <label for="field-title"><strong>Título:</strong></label>
                                <input type="text" class="form-control" name="title" id="field-title" value="{{ input_value($book, 'title') }}">
                            </li>
                            <li class="list-group-item">
                                <label for="field-author_name"><strong>Autor(a):</strong></label>
                                <input type="text" class="form-control" name="author_name" id="field-author_name" value="{{ input_value($book, 'author_name') }}">
                            </li>
                            <li class="list-group-item">
                                <label for="field-isbn"><strong>ISBN:</strong></label>
                                <input type="text" class="form-control" name="isbn" id="field-isbn" value="{{ input_value($book, 'isbn') }}">
                            </li>
                            <li class="list-group-item"><strong>Cadastrado em:</strong> {{$book->created_at}}</li>
                            <li class="list-group-item"><strong>Última atualização:</strong> {{$book->updated_at}}</li>
                            <li class="list-group-item">
                                <h3>Capa:</h3>
                                <label>alterar capa</label>
                                <input type="file" name="cover">
                                <br>
                                <img src="{{ $book->cover }}" class="img-responsive"/>
                            </li>
                            <li class="list-group-item">
                                <button class="btn btn-primary">Salvar Alterações</button>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
