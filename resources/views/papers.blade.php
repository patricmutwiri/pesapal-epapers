<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Papers</title>
        {{-- Patrick Mutwiri --}}
        {{-- twitter.com/patric_mutwiri --}}
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #000000;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
            input[type="checkbox"] {
                text-align: left;
                float: left;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                        <a href="{{ url('/orders') }}">My Orders</a>
                        <a href="{{ url('/newpaper') }}">Add Paper</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Papers
                </div>
                @if(count($papers) >= 1) 
                    <p> Please Check the paper(s) you'd like to buy. </p>
                    <hr/>
                    {{ Form::open() }}
                        @foreach ($papers as $paper)
                            <p> {{ Form::checkbox('papers', $paper->id, false) }} &nbsp; {{ ucwords(strtolower($paper->name)).'#'.$paper->id.' / '.date('D d M y',strtotime($paper->created_at)) }}</p>
                        @endforeach
                    {{ Form::submit('Pay Now') }}
                    {{ Form::close() }}
                @else
                    <div class="links">
                        {{ 'No Papers Found'}}
                    </div>
                @endif
            </div>
        </div>
    </body>
</html>
