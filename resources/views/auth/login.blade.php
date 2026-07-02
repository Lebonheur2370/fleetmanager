@extends('layouts.guest')

@section('title', 'Connexion')

@section('content')
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="mb-3">
            <label class="form-label">Mot de passe</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="remember" class="form-check-input" id="remember">
            <label class="form-check-label" for="remember">Se souvenir de moi</label>
        </div>

        <button type="submit" class="btn btn-success w-100">Se connecter</button>

        <p class="text-center mt-3 mb-0">
            <a href="{{ route('register') }}">Créer un compte gestionnaire</a>
        </p>
    </form>
@endsection
