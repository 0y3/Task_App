<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="{{ asset("assets/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css">
        {{-- <link href="{{ asset("assets/css/bootstrap_limitless.min.css") }}" rel="stylesheet" type="text/css"> --}}
        {{-- <link href="{{ asset("assets/css/layout.min.css") }}" rel="stylesheet" type="text/css"> --}}

        <title>App</title>

    </head>
    <body>
        <div id="app"></div>
        <!-- load Vite assets directly -->
        @vite(['resources/js/app.ts', 'resources/css/app.css'])
    </body>  
</html>
