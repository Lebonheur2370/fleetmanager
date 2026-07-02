@extends('layouts.app')

@section('title', 'Enregistrer un entretien')

@section('content')
    <h3 class="mb-4"><i class="fa-solid fa-screwdriver-wrench"></i> Enregistrer un entretien</h3>

    <div class="card shadow-sm" style="max-width: 750px;">
        <div class="card-body">
            <form method="POST" action="{{ route('entretiens.store') }}">
                @csrf
                @include('entretiens._form')

                <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-check"></i> Enregistrer l'entretien
                </button>
                <a href="{{ route('entretiens.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </form>
        </div>
    </div>
@endsection
