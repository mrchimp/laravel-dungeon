@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto p-4">
  <h1 class="text-4xl font-bold">{{ __('Register') }}</h1>

  <form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="my-3">
      <label for="name" class="label">{{ __('Name') }}</label>

      <input id="name" type="text" class="border border-gray-300 rounded text-lg p-2 w-full{{ $errors->has('name') ? ' is-danger' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

      @if ($errors->has('name'))
        <span class="help is-danger" role="alert">
          <strong>{{ $errors->first('name') }}</strong>
        </span>
      @endif
    </div>

    <div class="my-3">
      <label for="email" class="label">{{ __('E-Mail Address') }}</label>

      <input id="email" type="email" class="border border-gray-300 rounded text-lg p-2 w-full{{ $errors->has('email') ? ' is-danger' : '' }}" name="email" value="{{ old('email') }}" required>

      @if ($errors->has('email'))
        <span class="help is-danger" role="alert">
          <strong>{{ $errors->first('email') }}</strong>
        </span>
      @endif
    </div>

    <div class="my-3">
      <label for="password" class="label">{{ __('Password') }}</label>

      <input id="password" type="password" class="border border-gray-300 rounded text-lg p-2 w-full{{ $errors->has('password') ? ' is-danger' : '' }}" name="password" required>

      @if ($errors->has('password'))
        <span class="help is-danger" role="alert">
          <strong>{{ $errors->first('password') }}</strong>
        </span>
      @endif
    </div>

    <div class="my-3">
      <label for="password-confirm" class="label">{{ __('Confirm Password') }}</label>

      <div class="control">
        <input id="password-confirm" type="password" class="border border-gray-300 rounded text-lg p-2 w-full" name="password_confirmation" required>
      </div>
    </div>

    <div class="my-3">
      <a href="/" class="btn px-8 inline-block">Back</a>
      <button type="submit" class="btn px-8 btn-primary">
        {{ __('Register') }}
      </button>
    </div>
  </form>
</div>
@endsection
