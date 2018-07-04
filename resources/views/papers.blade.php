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
            div.paynow p {
                width: 100%;
                padding: 5px 0;
            }
            .paynow label{
                float: left;
                max-width: 48%;
            }
            .paynow input[type=text]{
                float: right;
                width: 50%
            }
            div.paynow p input[type=submit] {
                min-width: 200px;
                padding: 5px;
                width: 100%;
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

            <div class="content paynow">
                <div class="title m-b-md">
                    Papers
                </div>
                @if(count($papers) >= 1) 
                    <p>Please Check the paper(s) you'd like to buy.</p>
                    <hr/>
                    {{ Form::open(['url' => 'paynow']) }}
                        @foreach ($papers as $paper)
                            <p> {{ Form::checkbox('papers[]', json_encode(array('id' => $paper->id, 'price' => $paper->price)), false) }} &nbsp; {{ ucwords(strtolower($paper->name)).'#'.$paper->id.' / '.date('D d M y',strtotime($paper->created_at)).' @Ksh '.$paper->price }}</p>
                        @endforeach
                    <p>
                        {{ Form::label('firstname','First Name') }} 
                        {{ Form::text('firstname','',['required' => true]) }}
                    </p>
                    <p>
                        {{ Form::label('lastname','Last Name') }} 
                        {{ Form::text('lastname','',['required' => true]) }}
                    </p>
                    <p>
                        {{ Form::label('email','Email') }} 
                        {{ Form::text('email','',['required' => true]) }}
                    </p>
                    <p>
                        {{ Form::label('phonenumber','Phone') }} 
                        {{ Form::text('phonenumber') }}
                    </p>
                    <span>
                        {{ Form::label('dropoff','Dropoff Point/location description') }} 
                    </span>
                    <p>
                        {{ Form::textarea('dropoff') }}
                    </p>
                    <p>{{ Form::submit('Pay Now') }}</p>
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
