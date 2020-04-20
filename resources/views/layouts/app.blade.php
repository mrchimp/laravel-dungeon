<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Dungeon') }}</title>

  <script>
    window.pusher_key = '{{ config('broadcasting.connections.pusher.key') }}';
    @if(Auth::check())
      window.user_id = {{ Auth::user()->id }};
    @endif
  </script>
  <script src="{{ mix('js/app.js') }}" defer></script>

  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

  <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
  <div id="app">
    @yield('content')
  </div>
</body>
</html>
