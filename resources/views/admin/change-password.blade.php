@extends('admin.layout.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if($errors)
                        <p>{{ $errors->first() }}</p>
                    @endif
                    @if(isset($info))
                        <p>{{ $info }}</p>
                    @endif
                    <form action="" method="POST">
                        {!! csrf_field() !!}
                        <label for="current-password">Senha atual</label>
                        <input type="password" name="current_password">
                        <br>
                        <br>
                        <label for="new-password">Nova senha</label>
                        <input type="password" name="new_password">
                        <br>
                        <br>
                        <label for="cofirm-password">Confirmar nova senha</label>
                        <input type="password" name="new_password_confirmation">
                        <br>
                        <br>
                        <button type="submit">Trocar Senha</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
