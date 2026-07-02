@extends('layouts.app')

@section('title', 'Modifier ' . $chauffeur->nomComplet())

@section('content')
    <h3 class="mb-4"><i class="fa-solid fa-id-card"></i> Modifier {{ $chauffeur->nomComplet() }}</h3>

    <div class="card shadow-sm" style="max-width: 700px;">
        <div class="card-body">
            <form method="POST" action="{{ route('chauffeurs.update', $chauffeur) }}">
                @csrf
                @method('PUT')
                @include('chauffeurs._form')

                <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-check"></i> Enregistrer les modifications
                </button>
                <a href="{{ route('chauffeurs.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </form>
        </div>
    </div>
@endsection
