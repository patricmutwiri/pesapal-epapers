@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    You are logged in!
                    <hr/>
                    <p><b>My Orders</b></p>
                    <form action="" method="post">
                        @if(count($orders) > 1)
                            <table class="table table-bordered"> 
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Total</th>
                                        <th>Papers</th>
                                        <th>Order Date</th>
                                        <th>User</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>{{ $order->id}}</td>
                                            <td>{{ $order->total}}</td>
                                            <td>{{ json_encode($order->total) }}</td>
                                            <td>{{ $order->created_at }}</td>
                                            <td>{{ Auth::user($order->uid)->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="links">
                                {{ 'No Orders Found'}}
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
