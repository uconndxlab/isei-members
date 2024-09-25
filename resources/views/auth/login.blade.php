@extends('layouts.app')

@section('content')
    <h2>Login</h2>

    @if($errors->any())
        <div>
            <strong>{{ $errors->first() }}</strong>
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf

        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>

        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>

        <button type="submit">Login</button>
    </form>
@endsection
