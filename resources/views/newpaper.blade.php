<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>New Paper</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
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
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    New Paper
                </div>
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
                <div class="links">
                    {{ Form::open(array('url' => action('NewspaperController@store'),'files'=>'true')) }}
                    <p>
                        {{ Form::label('name', 'Paper Name') }}
                        {{ Form::text('name') }}
                    </p>
                    <p>
                        {{ Form::label('price', 'Paper Price') }}
                        {{ Form::number('price', '50') }}
                    </p>
                    <p>
                        {{ Form::label('file', 'Upload File') }}
                        {{ Form::file('file') }}
                    </p>
                    <p>
                        {{ Form::submit('Save Now') }}
                        {{csrf_field()}}
                    <p>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </body>
</html>
