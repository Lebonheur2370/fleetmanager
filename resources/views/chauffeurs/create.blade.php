@extends('layouts.app')

@section('title', 'Créer un chauffeur')

@section('content')
    <h3 class="mb-4"><i class="fa-solid fa-id-card"></i> Créer un chauffeur</h3>

    <div class="card shadow-sm" style="max-width: 700px;">
        <div class="card-body">
            <form method="POST" action="{{ route('chauffeurs.store') }}">
                @csrf
                @include('chauffeurs._form')

                <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-check"></i> Créer le chauffeur
                </button>
                <a href="{{ route('chauffeurs.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </form>
        </div>
    </div>
@endsection
