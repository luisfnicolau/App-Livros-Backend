@extends('admin.layout.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <p>{{ env('FACEBOOK_APP_') }}</p>
                    <p>Bem vindo ao sistema. Use o menu lateral para navegar.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
