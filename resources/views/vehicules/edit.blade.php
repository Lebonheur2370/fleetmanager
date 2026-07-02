@extends('layouts.app')

@section('title', 'Modifier ' . $vehicule->immatriculation)

@section('content')
    <h3 class="mb-4"><i class="fa-solid fa-car"></i> Modifier le véhicule {{ $vehicule->immatriculation }}</h3>

    <div class="card shadow-sm" style="max-width: 700px;">
        <div class="card-body">
            <form method="POST" action="{{ route('vehicules.update', $vehicule) }}">
                @csrf
                @method('PUT')
                @include('vehicules._form')

                <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-check"></i> Enregistrer les modifications
                </button>
                <a href="{{ route('vehicules.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </form>
        </div>
    </div>
@endsection
