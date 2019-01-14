<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Dungeon</title>
    </head>
    <body>
        <div id="app">
            <dungeon-interface></dungeon-interface>
        </div>
        <!-- <div id="cmdout"></div>
        <form id="cmdform" autocomplete="off">
            <input id="cmdin" name="cmdin">
            <button>Run</button>
        </form> -->
        <p>
            Logged in as: {{ Auth::user()->name }}
        </p>
        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
