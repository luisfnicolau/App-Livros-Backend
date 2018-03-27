@extends('admin.layout.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Usuário {{$user->name}}</div>
                @if($errors)
                    <span>{{$errors->first()}}</span>
                @endif
                <div class="panel-body">
                    <form action="{{ route('admin.user.update', $user->id) }}" method="POST">
                        {!! method_field('PUT') !!}
                        {!! csrf_field() !!}
                        <ul class="list-group">
                            <li class="list-group-item">
                                <img src="{{ $user->avatar }}"/>
                            </li>
                            <li class="list-group-item">
                                <label for="field-name"><strong>Nome:</strong></label>
                                <input type="text" class="form-control" name="name" id="field-name" value="{{ input_value($user, 'name') }}">
                            </li>
                            <li class="list-group-item">
                                <label for="field-email"><strong>Email:</strong></label>
                                <input type="email" class="form-control" name="email" id="field-email" value="{{ input_value($user, 'email') }}">
                            </li>
                            <li class="list-group-item"><strong>ID facebook:</strong> {{$user->facebook_id}}</li>
                            <li class="list-group-item"><strong>ID google:</strong> {{$user->google_id}}</li>
                            <li class="list-group-item"><strong>Cadastrado em:</strong> {{$user->created_at}}</li>
                            <li class="list-group-item">
                                <strong>Última atualização:</strong> {{$user->updated_at}}

                            </li>
                            <li class="list-group-item">
                                <button class="btn btn-primary">Salvar alterações</button>
                            </li>
                            <li class="list-group-item">
                                <form action="" method="POST">
                                    {!! method_field('PUT') !!}
                                    {!! csrf_field() !!}
                                    <label for="field-pricing">Plano</label>
                                    <select id="field-pricing" name="pricing_id">
                                        @foreach($pricings as $p)
                                            <option value="{{$p->id}}"
                                            @if($user->pricing_id==$p->id) selected
                                            @endif
                                            >{{$p->name}} - R$ {{$p->formatPrice()}}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-primary">Alterar Plano</button>
                                </form>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
