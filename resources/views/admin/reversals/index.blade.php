@extends('admin.layout.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Pedido de Estorno</div>

                <div class="panel-body">
                    <table class="table">
                        <thead>
                            <th>Data do pedido</th>
                            <th>Cumprido</th>
                            <th>#ID Pedido</th>
                        </thead>
                        <tbody>
                            @foreach($reversals as $reversal)
                            <tr>
                                <td>{{$reversal->created_at->format("d/m/Y h:m")}}</td>
                                <td>{{$reversal->fulfilled ? "Sim" : "Não"}}</td>
                                <td>{{$reversal->order->id}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {!! $reversals->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
