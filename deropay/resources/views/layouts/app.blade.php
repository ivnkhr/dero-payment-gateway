<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>DeroPay</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ url(mix('/assets/app.min.css')) }}" type="text/css">
        <script src="{{ url(mix('/assets/app.min.js')) }}"></script>
    </head>
    <body>
      
        <div class="container">
            @yield('content')
        </div>

        <nav class="navbar fixed-bottom navbar-light bg-light">
          <span class="navbar-brand mb-0">
            DeroPay &copy {{ date('Y') }}
          </span>
          @if( config('deropay.supporturl') )
            <a href="{{ config('deropay.supporturl') }}" target="_blank">Live Support</a>
          @endif
        </nav>

        
    </body>
</html>