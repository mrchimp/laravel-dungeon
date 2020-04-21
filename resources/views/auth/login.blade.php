@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto p-4">
    <h1 class="text-4xl font-bold">{{ __('Login') }}</h1>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="my-3">
            <label for="email" class="my-2">{{ __('E-Mail Address') }}</label>

            <div class="control">
                <input id="email" type="email" class="border border-gray-300 rounded text-lg p-2 w-full {{ $errors->has('email') ? ' border-red-600' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                @if ($errors->has('email'))
                    <span class="help border-red-600" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="my-3">
            <label for="password" class="my-2">{{ __('Password') }}</label>

            <div class="control">
                <input id="password" type="password" class="border border-gray-300 rounded text-lg p-2 w-full {{ $errors->has('password') ? ' border-red-600' : '' }}" name="password" required>

                @if ($errors->has('password'))
                    <span class="help border-red-600" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="my-3">
            <label class="checkbox" for="remember">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                {{ __('Remember Me') }}
            </label>
        </div>

        <div class="my-3">
            <button type="submit" class="btn btn-primary px-8">
                {{ __('Login') }}
            </button>

            <a href="{{ route('register') }}" class="btn px-8 inline-block">Register</a>
        </div>

        <div class="my-3">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            @endif
        </div>
    </form>
</div>
@endsection
