@extends('layouts.app')

@section('content')
    <div id="app">
        <dungeon-interface></dungeon-interface>
    </div>
    <!-- <div id="cmdout"></div>
    <form id="cmdform" autocomplete="off">
        <input id="cmdin" name="cmdin">
        <button>Run</button>
    </form> -->
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
    </form>
@endsection