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
                    @if(session()->has('message'))
                        <div class="alert alert-success">
                            <h2>{{ session()->get('message') }}</h2>
                        </div>
                    @endif
                    @if(session()->has('error'))
                        <div class="alert alert-error">
                            <h2>{{ session()->get('error') }}</h2>
                        </div>
                    @endif
                    You are logged in!
                    <hr/>
                    <p><b>My Orders</b></p>
                    <form action="" method="post">
                        @if(count($orders) >= 1)
                            <table class="table table-bordered"> 
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Total</th>
                                        <th>Papers</th>
                                        <th>Order Date</th>
                                        <th>User</th>
                                        <th>Dropoff/Location</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $key => $order)
                                        <tr>
                                            <td>{{ $order->id}}</td>
                                            <td>{{ $order->amount}}</td>
                                            <td>
                                                @if(!empty(count($orders[$key]['mypapers'])))
                                                    <ul>
                                                        <li><a href="file/{{ $orders[$key]['mypapers']['path'] }}">{{ $orders[$key]['mypapers']['papername'] }}</a></li>
                                                    </ul>
                                                @else
                                                {{ 'N/A' }}
                                                @endif
                                            </td>
                                            <td>{{ $order->created_at }}</td>
                                            <td>{{ Auth::user($order->uid)->name }}</td>
                                            <td>{{ $order->dropoff }}</td>
                                            <td>{{ $order->status }}</td>
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
