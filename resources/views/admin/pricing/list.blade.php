@extends('admin.layout.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>
                @if($errors)
                    <p>{{ $errors->first() }}</p>
                @endif
                <div class="panel-body">
                    <h2>$ Planos</h2>
                    <table class="table">
                        <thead>
                            <td>Nome</td>
                            <td>Preço</td>
                            <td>última alteração</td>
                        </thead>
                        <tbody>
                            @foreach($pricings as $p)
                            <tr>
                                <td>{{$p->name}}</td>
                                <td>
                                    <form action="{{ route('pricing.update', $p->id) }}" method='POST'>
                                        {!! csrf_field() !!}
                                        {!! method_field('PUT') !!}
                                        <span><strong>R$ {{$p->formatPrice()}}</strong></span>
                                        <input type="number" name="price" value="{{$p->getPrice()}}" step="0.05" min="0">
                                        <button type="submit">Salvar</button>
                                    </form>
                                </td>
                                <td>{{$p->updated_at}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {!! $pricings->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
