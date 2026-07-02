@extends('layouts.guest')

@section('title', 'Changer votre mot de passe')

@section('content')
    <p class="text-muted small">Pour des raisons de sécurité, vous devez définir un nouveau mot de passe avant de continuer.</p>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nouveau mot de passe</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success w-100">Valider</button>
    </form>
@endsection
