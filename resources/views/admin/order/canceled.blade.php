@extends('admin.layout.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Pedidos Cancelados</div>

                <div class="panel-body">
                    <table class="table">
                        <thead>
                            <th>Data do pedido</th>
                            <th>Total do Pedido</th>
                            <th>Porcentagem do APP</th>
                            <th>Nota</th>
                            <th>Data de Cancelamento</th>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>{{$order->created_at->format("d/m/Y h:m")}}</td>
                                <td>{{$order->total}}</td>
                                <td>{{$order->total * \App\Model\Order::$SERVICE_PORCENTAGE_IN_ORDER}}</td>
                                <td>{{$order->rating}}</td>
                                <td>{{$order->canceled_date->format("d/m/Y h:m")}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {!! $orders->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
