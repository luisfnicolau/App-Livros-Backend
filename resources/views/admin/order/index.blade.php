@extends('admin.layout.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Pedidos</div>

                <div class="panel-body">
                    <h3>Média avaliações: {{ number_format($average_rating, 2) }}</h3>
                    <h3>Média Tempo de Entrega: {{ number_format($average_delivery_date) }} dias</h3>
                    <table class="table">
                        <thead>
                            <th>#ID do Pedido</th>
                            <th>Data do pedido</th>
                            <th>Total do Pedido</th>
                            <th>Porcentagem do APP</th>
                            <th>Nota</th>
                            <th>Data de Entrega</th>
                            <th>Tempo de Entrega(Dias)</th>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>{{$order->id}}</td>
                                <td>{{$order->created_at->format("d/m/Y h:m")}}</td>
                                <td>{{$order->total}}</td>
                                <td>{{number_format($order->total * \App\Model\Order::$SERVICE_PORCENTAGE_IN_ORDER + 1,2)}}</td>
                                <td>{{$order->rating}}</td>
                                <td>{{$order->delivery_date->format("d/m/Y h:m")}}</td>
                                <td>{{$order->delivery_date->diffInDays($order->created_at)}}</td>
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
