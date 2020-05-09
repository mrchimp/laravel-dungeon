@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
  <h1 class="text-3xl">Devious Dungeon</h1>
  <p class="mt-4 mb-8 rounded bg-red-300 px-4 py-3">
    <strong>Warning!</strong> This game is a work in progress. Accounts, progress and content may be wiped at any point without notice.
  </p>
  <p class="mt-4">
    <a class="btn btn-primary" href="/login">Log in</a>
    <a class="btn" href="/register">Register</a>
  </p>
</div>
@endsection
