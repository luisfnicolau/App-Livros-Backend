@extends('admin.layout.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Usuários</div>

                <div class="panel-body">
                    <form action="" method="GET">
                        <label for="field-q">Pesquisar usuários</label>
                        <input id="field-q" type="text" name="q"/>
                        <button type="submit" class="btn btn-primary">Pesquisar</button>
                    </form>
                    <br>
                    <table class="table">
                        <thead>
                            <td>Nome</td>
                            <td>E-mail</td>
                            <td>Facebook id</td>
                            <td>Google id</td>
                            <td>Cadastrado em</td>
                            <td>Excluir</td>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td><a href="{{ route('admin.user.edit', $user->id) }}" title="Editar {{ $user->name }}">{{$user->name}}</a></td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->facebook_id}}</td>
                                <td>{{$user->google_id}}</td>
                                <td>{{$user->created_at}}</td>
                                <td>
                                <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST">
                                    {!! method_field('DELETE') !!}
                                    {!! csrf_field() !!}
                                    <button class="btn btn-default" type="submit">Excluir</button>
                                </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {!! $users->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
