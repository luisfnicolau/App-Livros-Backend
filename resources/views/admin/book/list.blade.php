@extends('admin.layout.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="{{route('book.index')}}">Livros</a>
                </div>

                <div class="panel-body">
                    <form action="{{route('book.crawl-api') }}" method="POST">
                        {!! csrf_field() !!}
                        <h3>Adicionar livros da api externa</h3>
                        <label for="field-q">Busca geral</label>
                        <input id="field-q" type="text" name="q"/>
                        <em>OU</em>
                        <label for="field-isbn">ISBN</label>
                        <input id="field-isbn" type="text" name="isbn" pattern="\d{9}|\d{13}" maxlength="13" minlength="9" />
                        <button type="submit" class="btn btn-danger">Adicionar novos livros</button>
                    </form>
                    <br>
                    <form action="" method="GET">
                        <h3>Pesquisar livros nesta aplicação</h3>
                        <label for="field-q">Pesquisar livros</label>
                        <input id="field-q" type="text" name="q"/>
                        <button type="submit" class="btn btn-primary">Pesquisar</button>
                    </form>
                    <table class="table">
                        <thead>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>ISBN</th>
                            <th>Cadastrado em</th>
                            <th>Excluir</th>
                        </thead>
                        <tbody>
                            @foreach($books as $book)
                            <tr>
                                <td><a href="{{ route('book.edit', $book->id) }}" title="Editar {{ $book->name }}">{{$book->title}}</a></td>
                                <td>{{$book->author_name}}</td>
                                <td>{{$book->isbn}}</td>
                                <td>{{$book->created_at}}</td>
                                <td>
                                <form action="{{ route('book.destroy', $book->id) }}" method="POST">
                                    {!! method_field('DELETE') !!}
                                    {!! csrf_field() !!}
                                    <button class="btn btn-default" type="submit">Excluir</button>
                                </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {!! $books->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
