@extends('layouts.app')

@section('content')
  <div id="app">
    <dungeon-interface></dungeon-interface>
  </div>
{{--
  <p>
    Logged in as: {{ Auth::user()->name }} -
    <a href="{{ route('logout') }}"
      onclick="event.preventDefault();
      document.getElementById('logout-form').submit();">
      {{ __('Logout') }}
    </a>
  </p>

  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
  </form> --}}
@endsection
